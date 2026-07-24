<div class="card">
    <div class="card-header pb-0">
        <h6>Purchase Order List</h6>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="display table nowrap" style="width: 100%;" id="myTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>PO Number</th>
                        <th>Supp Name</th>
                        <th>Supp Number</th>
                        <th>Status</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $get = $con->query("SELECT * FROM purchase_order ORDER BY created_at DESC");
                    while ($row = $get->fetch_assoc()) {
                        $id = $row['id'];
                        
                        $product_names = [];
                        $product_qtys  = [];
                        $product_prices = [];
                        $product_totals = [];

                        $stmt_item = $con->prepare("SELECT * FROM purchase_order_items WHERE po_id = ?");
                        $stmt_item->bind_param("i", $id);
                        $stmt_item->execute();
                        $getProduct = $stmt_item->get_result();

                        while ($product = $getProduct->fetch_assoc()) {
                            $product_names[]  = htmlspecialchars($product['product_name']);
                            $product_qtys[]   = htmlspecialchars($product['qty']);
                            $product_prices[] = 'Rp' . number_format($product['price'], 2);
                            $product_totals[] = 'Rp' . number_format($product['total'], 2);
                        }
                        $stmt_item->close();
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['po_number']) ?></td>
                            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                            <td><?= htmlspecialchars($row['supplier_number']) ?></td>
                            <td><span class="badge bg-secondary" style="text-transform: capitalize"><?= htmlspecialchars($row['status']) ?></span></td>
                            <td><?= implode('<br>', $product_names) ?></td>
                            <td><?= implode('<br>', $product_qtys) ?></td>
                            <td><?= implode('<br>', $product_prices) ?></td>
                            <td><?= implode('<br>', $product_totals) ?></td>
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
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>