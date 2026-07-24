<div class="card">
    <div class="card-header pb-0">
        <h6>Customer Order List</h6>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="display table nowrap" style="width: 100%;" id="myTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Inv Number</th>
                        <th>Date</th>
                        <th>Cust Name</th>
                        <th>Pay Method</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Sub Total</th>
                        <th>Total</th>
                        <th>Pay</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $get = $con->query("SELECT * FROM `order` ORDER BY created_at DESC");
                    while ($row = $get->fetch_assoc()) {
                        $id = $row['id'];

                        $product_names = [];
                        $product_qtys = [];
                        $product_prices = [];
                        $product_totals = [];

                        $stmt_item = $con->prepare("SELECT * FROM order_items WHERE order_id = ?");
                        $stmt_item->bind_param("i", $id);
                        $stmt_item->execute();
                        $getProduct = $stmt_item->get_result();

                        while ($product = $getProduct->fetch_assoc()) {
                            $product_names[] = htmlspecialchars($product['product_name']);
                            $product_qtys[] = htmlspecialchars($product['product_qty']);
                            $product_prices[] = 'Rp' . number_format($product['product_price'], 2);
                            $product_totals[] = 'Rp' . number_format($product['total_amount'], 2);
                        }
                        $stmt_item->close();
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['inv_number']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['cust_name']) ?></td>
                            <td><span class="badge bg-secondary"
                                    style="text-transform: capitalize"><?= htmlspecialchars($row['payment_method']) ?></span>
                            </td>
                            <td><?= implode('<br>', $product_names) ?></td>
                            <td><?= implode('<br>', $product_qtys) ?></td>
                            <td><?= implode('<br>', $product_prices) ?></td>
                            <td><?= implode('<br>', $product_totals) ?></td>
                            <td>Rp<?= number_format($row['total_amount'], 2) ?></td>
                            <td>Rp<?= number_format($row['pay_amount'], 2) ?></td>
                            <td>Rp<?= number_format($row['change_amount'], 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive tr {
            font-size: 12px;
        }

        .dataTables_info,
        .dataTables_paginate,
        .dataTables_paginate a {
            font-size: 12px !important;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>