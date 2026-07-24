<?php
$session_id = session_id();
date_default_timezone_set('Asia/Jakarta');

if (!function_exists('format_currency')) {
    function format_currency($number)
    {
        return number_format((float) $number, 0, ',', '.');
    }
}

if (!function_exists('generate_inv_number')) {
    function generate_inv_number($con)
    {
        do {
            $number = 'INV-';
            for ($i = 0; $i < 10; $i++) {
                $number .= mt_rand(0, 9);
            }
            $stmt = $con->prepare("SELECT 1 FROM `order` WHERE inv_number = ? LIMIT 1");
            $stmt->bind_param("s", $number);
            $stmt->execute();
            $check = $stmt->get_result();
            $exists = ($check && $check->num_rows > 0);
            $stmt->close();
        } while ($exists);

        return $number;
    }
}

$preview_inv_number = generate_inv_number($con);

// 1. ADD TO CART
if (isset($_POST['add'])) {
    $product_id = (int) ($_POST['product'] ?? 0);
    $product_name = $_POST['product_name'] ?? '';
    $qty = (int) ($_POST['qty'] ?? 1);
    $created_at = date('Y-m-d H:i:s');

    $stmt_p = $con->prepare("
        SELECT p.product_price AS price, COALESCE(SUM(po.qty), 0) AS stock 
        FROM product p 
        LEFT JOIN purchase_order_items po ON p.product_name = po.product_name 
        WHERE p.id = ? 
        GROUP BY p.id 
        LIMIT 1
    ");
    $stmt_p->bind_param("i", $product_id);
    $stmt_p->execute();
    $res_p = $stmt_p->get_result();

    if ($res_p && $res_p->num_rows > 0) {
        $p_data = $res_p->fetch_assoc();
        $price = (float) $p_data['price'];
        $current_stock = (int) $p_data['stock'];
        $stmt_p->close();

        if ($qty > $current_stock) {
            echo "<script>
                alert('Failed! Purchase quantity ($qty) exceeds available stock ($current_stock).');
                document.location.href='index.php?page=order';
            </script>";
            exit;
        }

        if ($qty <= 0) {
            echo "<script>
                alert('Failed! Purchase quantity must be greater than 0.');
                document.location.href='index.php?page=order';
            </script>";
            exit;
        }
        
        $total = $price * $qty;

        $stmt = $con->prepare("INSERT INTO cart (session_id, product_id, product_name, price, qty, total, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisdids", $session_id, $product_id, $product_name, $price, $qty, $total, $created_at);

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>document.location.href='index.php?page=order';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to add item to cart: " . addslashes($stmt->error) . "');</script>";
            $stmt->close();
        }
    } else {
        $stmt_p->close();
        echo "<script>alert('Product not found in database.'); document.location.href='index.php?page=order';</script>";
        exit;
    }
}

// 2. SAVE ORDER
if (isset($_POST['save'])) {
    $inv_number = $_POST['inv_number'] ?? generate_inv_number($con);
    $date = $_POST['date'] ?? date('Y-m-d');
    $cust_name = $_POST['cust_name'] ?? '';
    $payment_method = $_POST['pay_method'] ?? 'cash';
    $total = (float) ($_POST['total'] ?? 0);
    $pay = (float) ($_POST['pay'] ?? 0);
    $change = (float) ($_POST['change'] ?? 0);

    $stmt_cart = $con->prepare("SELECT * FROM cart WHERE session_id = ?");
    $stmt_cart->bind_param("s", $session_id);
    $stmt_cart->execute();
    $result = $stmt_cart->get_result();

    if (!$result || $result->num_rows === 0) {
        echo "<script>alert('Cart is empty. Please add products first.'); document.location.href='index.php?page=order';</script>";
        exit;
    }

    $con->begin_transaction();

    try {
        $stmt_order = $con->prepare("INSERT INTO `order` (inv_number, date, cust_name, total_amount, pay_amount, change_amount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_order->bind_param("sssddd", $inv_number, $date, $cust_name, $total, $pay, $change);
        
        if (!$stmt_order->execute()) {
            throw new Exception($stmt_order->error);
        }
        $inv_id = $con->insert_id;
        $stmt_order->close();

        $stmt_item = $con->prepare("INSERT INTO order_items (order_id, order_inv_number, product_id, product_name, product_price, product_qty, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");

        while ($row = $result->fetch_assoc()) {
            $product_id_row = (int) $row['product_id'];
            $price_row = (float) $row['price'];
            $qty_row = (int) $row['qty'];
            $total_row = (float) $row['total'];

            $stmt_item->bind_param("isisidd", $inv_id, $inv_number, $product_id_row, $row['product_name'], $price_row, $qty_row, $total_row);

            if (!$stmt_item->execute()) {
                throw new Exception($stmt_item->error);
            }
        }
        $stmt_item->close();

        $stmt_del = $con->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt_del->bind_param("s", $session_id);
        $stmt_del->execute();
        $stmt_del->close();

        $con->commit();

        echo "<script>alert('Order submitted successfully!'); document.location.href='index.php?page=order-history';</script>";
        exit;

    } catch (Exception $e) {
        $con->rollback();
        echo "<script>alert('Failed to save order: " . addslashes($e->getMessage()) . "'); document.location.href='index.php?page=order';</script>";
        exit;
    }
}

// 3. DELETE CART ITEM
if (isset($_GET['deletecart'])) {
    $cart_id = (int) $_GET['deletecart'];

    $stmt = $con->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
    $stmt->bind_param("is", $cart_id, $session_id);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Item removed successfully'); document.location.href='index.php?page=order';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to remove item: " . addslashes($stmt->error) . "'); document.location.href='index.php?page=order';</script>";
        $stmt->close();
        exit;
    }
}
?>

<div style="padding: 10px;">
    <div class="row">
        <!-- Add to Cart Form -->
        <div class="col-12">
            <div class="card shadow p-3 mt-2">
                <form action="" method="post" id="add_cart_form">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="product" class="form-label">Product</label>
                            <select name="product" class="form-control" id="product" required>
                                <option value="" disabled selected>Select Product</option>
                                <?php
                                $getProduct = $con->query("SELECT p.*, COALESCE(SUM(po.qty), 0) as stock FROM product p LEFT JOIN purchase_order_items po ON p.product_name = po.product_name GROUP BY p.id");
                                while ($row = $getProduct->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['id']) . '" 
                                    data-name="' . htmlspecialchars($row['product_name'] ?? '') . '"
                                    data-price="' . htmlspecialchars($row['product_price'] ?? 0) . '"
                                    data-stock="' . htmlspecialchars($row['stock'] ?? 0) . '">'
                                        . htmlspecialchars($row['product_name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="product_name" value="" id="product_name">
                        <div class="col-md-6 mb-2">
                            <label for="price" class="form-label">Price (DB)</label>
                            <input type="number" step="any" class="form-control" id="price" value="0" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" step="any" class="form-control" name="stock" id="stock" readonly>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" value="1" min="1" name="qty" id="qty"
                                oninput="checkStockAndTotal()" required>
                            <small id="qty_warning" class="text-danger" style="display:none; font-size: 11px;">
                                Qty out of stock!
                            </small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="item_total" class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="item_total" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2" name="add">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>

        <!-- Cart Table & Order Submit Form -->
        <div class="col-12">
            <div class="card shadow p-3 mt-3">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt_cart_list = $con->prepare("SELECT * FROM cart WHERE session_id = ?");
                            $stmt_cart_list->bind_param("s", $session_id);
                            $stmt_cart_list->execute();
                            $get = $stmt_cart_list->get_result();
                            $no = 1;
                            $grand_total = 0;

                            if ($get->num_rows > 0) {
                                while ($row = $get->fetch_assoc()) {
                                    $grand_total += $row['total'];
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row["product_name"] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($row["qty"]); ?></td>
                                        <td>Rp <?= format_currency($row['price']); ?></td>
                                        <td>Rp <?= format_currency($row['total']); ?></td>
                                        <td>
                                            <a href="index.php?page=order&deletecart=<?= $row['id']; ?>"
                                                class="btn btn-danger btn-sm px-2 py-1"
                                                onclick="return confirm('Remove item from cart?')">
                                                <i class="bi bi-trash text-white"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No items in cart.</td>
                                </tr>
                            <?php }
                            $stmt_cart_list->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <form method="POST" class="mt-3">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2">
                            <label for="grand_total" class="form-label"><b>Total</b></label>
                            <input type="text" class="form-control" name="total" id="grand_total"
                                value="<?= $grand_total; ?>" readonly>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="pay" class="form-label"><b>Pay</b></label>
                            <input type="number" step="any" class="form-control" name="pay" id="pay"
                                oninput="calculateChange()" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="change" class="form-label"><b>Change</b></label>
                            <input type="text" class="form-control" name="change" id="change" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="inv_number" class="form-label">Inv Number</label>
                            <input type="text" class="form-control" name="inv_number" id="inv_number"
                                value="<?= htmlspecialchars($preview_inv_number); ?>" readonly>
                            <small class="text-muted">Auto-generated by system</small>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="cust_name" class="form-label">Cust Name</label>
                            <input type="text" class="form-control" name="cust_name" id="cust_name" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="pay_method" class="form-label">Payment Method</label>
                            <select name="pay_method" id="pay_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="qris">Qris</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100" name="save">
                        Save Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateChange() {
        let grandTotal = parseFloat(document.getElementById('grand_total').value) || 0;
        let pay = parseFloat(document.getElementById('pay').value) || 0;
        let change = pay - grandTotal;

        document.getElementById('change').value = change >= 0 ? change.toFixed(2) : '0.00';
    }

    function checkStockAndTotal() {
        let price = parseFloat(document.getElementById('price').value) || 0;
        let qtyInput = document.getElementById('qty');
        let stockInput = parseFloat(document.getElementById('stock').value) || 0;
        let submitBtn = document.querySelector('#add_cart_form button[name="add"]');
        let warningText = document.getElementById('qty_warning');

        let qty = parseFloat(qtyInput.value) || 0;

        if (qty > stockInput) {
            if (warningText) warningText.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            if (warningText) warningText.style.display = 'none';
            submitBtn.disabled = false;
        }

        let total = price * qty;
        document.getElementById('item_total').value = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product');
        if (productSelect) {
            productSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const defaultName = selectedOption.getAttribute('data-name');
                const defaultPrice = selectedOption.getAttribute('data-price');
                const defaultStock = selectedOption.getAttribute('data-stock');

                document.getElementById('product_name').value = defaultName || '';
                document.getElementById('price').value = defaultPrice || 0;
                document.getElementById('stock').value = defaultStock || 0;

                const qtyInput = document.getElementById('qty');
                if (qtyInput) {
                    qtyInput.max = defaultStock || 0;
                }

                checkStockAndTotal();
            });
        }
    });
</script>