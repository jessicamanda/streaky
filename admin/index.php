<?php
session_start();
include "../koneksi.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>Dashboard | adminHMD</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>
        <?php include 'layouts/sidebar.php'; ?>

        <div class="admin-main">
            <?php include 'layouts/navbar.php'; ?>

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <?php
                    $page = $_GET['page'];
                    if (isset($_GET['page'])) {
                        include "pages/$page.php";
                    } else {
                        include "pages/dashboard.php";
                    }
                    ?>
                </div>

        </div>
        </main>

        <footer class="admin-footer">
            <div class="container-fluid px-3 px-lg-4">
                <span>Copyright 2026 adminHMD. <br> Developed by <a target="_blank" class="fw-bold text-success"
                        href="https://github.com/HasanMahmudDev">Md. Hasan Mahmud</a> • Distributed by <a
                        target="_blank" class="fw-bold text-success" href="https://themewagon.com">ThemeWagon</a>
                </span>
                <span>Professional dashboard template.</span>
            </div>
        </footer>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>