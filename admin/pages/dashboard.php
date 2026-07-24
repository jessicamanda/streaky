<?php
$q_product = $con->query("SELECT COUNT(*) AS total FROM product");
$total_product = $q_product->fetch_assoc()['total'] ?? 0;
$q_orders = $con->query("SELECT COUNT(*) AS total FROM `order`");
$total_orders = $q_orders->fetch_assoc()['total'] ?? 0;
$q_cash_out = $con->query("SELECT COALESCE(SUM(total), 0) AS total FROM purchase_order_items");
$cash_out = $q_cash_out->fetch_assoc()['total'] ?? 0;
$q_cash_in = $con->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM `order`");
$cash_in = $q_cash_in->fetch_assoc()['total'] ?? 0;
$current_year = date('Y');
$chart_data = [];
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$q_chart = $con->query("SELECT MONTH(date) AS bulan, COUNT(*) AS total_order 
                        FROM `order` 
                        WHERE YEAR(date) = '$current_year' 
                        GROUP BY MONTH(date)");
$monthly_orders = [];
while ($row = $q_chart->fetch_assoc()) {
    $monthly_orders[$row['bulan']] = (int) $row['total_order'];
}
$max_orders = !empty($monthly_orders) ? max($monthly_orders) : 1;
$q_customers = $con->query("SELECT cust_name, date, total_amount 
                           FROM `order` 
                           ORDER BY id DESC LIMIT 5");
?>

<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Overview</p>
                <h1 class="h3 mb-1">Dashboard</h1>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Product</span>
                    <span class="metric-icon"><i class="bi bi-boxes" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?= number_format($total_product, 0, ',', '.') ?></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Orders</span>
                    <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?= number_format($total_orders, 0, ',', '.') ?></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Cash Out</span>
                    <span class="metric-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><small>Rp</small><?= number_format($cash_out, 0, ',', '.') ?></div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Cash In</span>
                    <span class="metric-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><small>Rp</small><?= number_format($cash_in, 0, ',', '.') ?></div>
            </article>
        </div>
    </section>

    <section class="row g-3 mt-1">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                            <span>Orders Performance (<?= $current_year ?>)</span>
                        </h2>
                    </div>
                </div>

                <div class="chart-bars d-flex align-items-end justify-content-between px-2" style="height: 180px;"
                    aria-label="Sales performance chart">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $count = $monthly_orders[$m] ?? 0;
                        $height = ($max_orders > 0 && $count > 0) ? round(($count / $max_orders) * 100) : 6;
                        ?>
                        <div class="chart-column text-center h-100 d-flex flex-column justify-content-end align-items-center"
                            style="width: 7%;">
                            <span style="height: <?= $height ?>%; width: 100%; border-radius: 4px; display: block;"
                                class="bg-success" title="<?= $count ?> Orders"></span>
                            <small class="mt-2 text-muted" style="font-size: 11px;"><?= $months[$m - 1] ?></small>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <section class="panel mt-3">
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Customers</span>
                </h2>
                <p class="text-muted mb-0">Latest customer across the shop.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($q_customers && $q_customers->num_rows > 0) {
                        while ($cust = $q_customers->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($cust['cust_name'] ?? 'N/A') ?></td>
                                <td><?= date('d M Y', strtotime($cust['date'])) ?></td>
                                <td><b>Rp <?= number_format($cust['total_amount'], 0, ',', '.') ?></b></td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No customer transactions found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>