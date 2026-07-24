<?php
$session_id = session_id();
date_default_timezone_set('Asia/Jakarta');

if (!function_exists('format_currency')) {
    function format_currency($number)
    {
        return number_format((float) $number, 2, '.', ',');
    }
}

if (!function_exists('generate_po_number')) {
    function generate_po_number($con)
    {
        do {
            $number = 'PO-';
            for ($i = 0; $i < 10; $i++) {
                $number .= mt_rand(0, 9);
            }
            $stmt = $con->prepare("SELECT 1 FROM `purchase_order` WHERE po_number = ? LIMIT 1");
            $stmt->bind_param("s", $number);
            $stmt->execute();
            $check = $stmt->get_result();
            $exists = ($check && $check->num_rows > 0);
            $stmt->close();
        } while ($exists);

        return $number;
    }
}

$preview_po_number = generate_po_number($con);

if (isset($_POST['add'])) {
    $product_name = $_POST['product'] ?? '';
    $qty = (int) ($_POST['qty'] ?? 1);
    $created_at = date('Y-m-d H:i:s');

    $stmt_p = $con->prepare("SELECT product_price as price FROM product WHERE product_name = ? LIMIT 1");
    $stmt_p->bind_param("s", $product_name);
    $stmt_p->execute();
    $res_p = $stmt_p->get_result();

    if ($res_p && $res_p->num_rows > 0) {
        $p_data = $res_p->fetch_assoc();
        $price = (float) $p_data['price'];
        $stmt_p->close();

        $total = $price * $qty;

        $stmt = $con->prepare("INSERT INTO cart (session_id, product_name, price, qty, total, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdids", $session_id, $product_name, $price, $qty, $total, $created_at);

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>document.location.href='index.php?page=purchase';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to add item to cart: " . addslashes($stmt->error) . "');</script>";
            $stmt->close();
        }
    } else {
        $stmt_p->close();
        echo "<script>alert('Product not found in database.'); document.location.href='index.php?page=purchase';</script>";
        exit;
    }
}

if (isset($_POST['save'])) {
    $po_number = $_POST['po_number'] ?? generate_po_number($con);
    $supplier_name = $_POST['supplier_name'] ?? '';
    $supplier_number = $_POST['supplier_number'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    $stmt_cart = $con->prepare("SELECT * FROM cart WHERE session_id = ?");
    $stmt_cart->bind_param("s", $session_id);
    $stmt_cart->execute();
    $result = $stmt_cart->get_result();

    if (!$result || $result->num_rows === 0) {
        echo "<script>alert('Cart is empty. Please add products first.'); document.location.href='index.php?page=purchase';</script>";
        exit;
    }

    $con->begin_transaction();

    try {
        $stmt_po = $con->prepare("INSERT INTO purchase_order (po_number, supplier_name, supplier_number, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt_po->bind_param("ssss", $po_number, $supplier_name, $supplier_number, $status);
        if (!$stmt_po->execute()) {
            throw new Exception($stmt_po->error);
        }
        $po_id = $con->insert_id;
        $stmt_po->close();

        $stmt_item = $con->prepare("INSERT INTO purchase_order_items (po_id, po_number, product_name, price, qty, total) VALUES (?, ?, ?, ?, ?, ?)");

        while ($row = $result->fetch_assoc()) {
            $price_row = (float) $row['price'];
            $qty_row = (int) $row['qty'];
            $total_row = (float) $row['total'];

            $stmt_item->bind_param("issdid", $po_id, $po_number, $row['product_name'], $price_row, $qty_row, $total_row);

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

        echo "<script>alert('Purchase Order submitted successfully!'); document.location.href='index.php?page=purchase';</script>";
        exit;

    } catch (Exception $e) {
        $con->rollback();
        echo "<script>alert('Failed to save Purchase Order: " . addslashes($e->getMessage()) . "'); document.location.href='index.php?page=purchase';</script>";
        exit;
    }
}

if (isset($_GET['deletecart'])) {
    $cart_id = (int) $_GET['deletecart'];

    $stmt = $con->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
    $stmt->bind_param("is", $cart_id, $session_id);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Item removed successfully'); document.location.href='index.php?page=purchase';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to remove item: " . addslashes($stmt->error) . "'); document.location.href='index.php?page=purchase';</script>";
        $stmt->close();
        exit;
    }
}
?>

<div style="padding: 10px;">
    <div class="row">
        <div class="col-12">
            <div class="card shadow p-3 mt-2">
                <form action="" method="post" id="add_cart_form">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="product" class="form-label">Product</label>
                            <select name="product" class="form-control" id="product" required>
                                <option value="" disabled selected>Select Product</option>
                                <?php
                                $getProduct = $con->query("SELECT * FROM product");
                                while ($row = $getProduct->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['product_name']) . '" data-price="' . htmlspecialchars($row['product_price'] ?? 0) . '">'
                                        . htmlspecialchars($row['product_name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="price" class="form-label">Price (DB)</label>
                            <input type="number" step="any" class="form-control" id="price" value="0" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" value="1" name="qty" id="qty"
                                oninput="showTotal()" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="total" class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="total" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2" name="add">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>

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

                            if ($get->num_rows > 0) {
                                while ($row = $get->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row["product_name"] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($row["qty"]); ?></td>
                                        <td>Rp<?= format_currency($row['price']); ?></td>
                                        <td>Rp<?= format_currency($row['total']); ?></td>
                                        <td>
                                            <a href="index.php?page=purchase&deletecart=<?= $row['id']; ?>"
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
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="po_number" class="form-label">PO Number</label>
                            <input type="text" class="form-control" name="po_number" id="po_number"
                                value="<?= htmlspecialchars($preview_po_number); ?>" readonly>
                            <small class="text-muted">Auto-generated by system</small>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="supplier_name" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name" id="supplier_name" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="supplier_number" class="form-label">Supplier Contact / Ref No.</label>
                            <input type="text" class="form-control" name="supplier_number" id="supplier_number"
                                required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="status" class="form-label">Status Order</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="received">Received</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100" name="save">
                        Save Purchase Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showTotal() {
        let price = parseFloat(document.getElementById('price').value) || 0;
        let qty = parseFloat(document.getElementById('qty').value) || 0;
        let total = price * qty;

        document.getElementById('total').value = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product');
        if (productSelect) {
            productSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const defaultPrice = selectedOption.getAttribute('data-price');
                if (defaultPrice) {
                    document.getElementById('price').value = defaultPrice;
                }
                showTotal();
            });
        }
        showTotal();
    });
</script>