<?php
session_start();
define('APP_BOOTSTRAPPED', true);
require __DIR__ . '/koneksi.php';
require __DIR__ . '/app.php';

handle_request($con);

$page = (string) ($_GET['page'] ?? '');
$publicPages = ['login', 'register'];
$privatePages = ['dashboard', 'daily-streak', 'calendar', 'todo-list', 'settings', 'add-frs'];
if ($page === '') {
    $page = empty($_SESSION['user_id']) ? 'login' : 'dashboard';
}
if (!in_array($page, array_merge($publicPages, $privatePages), true)) {
    http_response_code(404);
    $page = '404';
}

$user = null;
$courses = [];
$todoItems = [];
if (in_array($page, $privatePages, true)) {
    if (empty($_SESSION['user_id'])) {
        redirect_to('login');
    }

    $userId = (int) $_SESSION['user_id'];
    $userQuery = mysqli_prepare($con, 'SELECT * FROM users WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($userQuery, 'i', $userId);
    mysqli_stmt_execute($userQuery);
    $user = mysqli_stmt_get_result($userQuery)->fetch_assoc();
    mysqli_stmt_close($userQuery);
    if (!$user) {
        $_SESSION = [];
        redirect_to('login');
    }

    if ($page === 'dashboard') {
        $courseCheck = mysqli_prepare($con, 'SELECT id FROM courses WHERE user_id = ? LIMIT 1');
        mysqli_stmt_bind_param($courseCheck, 'i', $userId);
        mysqli_stmt_execute($courseCheck);
        $hasCourses = mysqli_stmt_get_result($courseCheck)->num_rows > 0;
        mysqli_stmt_close($courseCheck);
        if (!$hasCourses) {
            redirect_to('add-frs');
        }
    }

    $courseQuery = mysqli_prepare($con, 'SELECT * FROM courses WHERE user_id = ? ORDER BY id');
    mysqli_stmt_bind_param($courseQuery, 'i', $userId);
    mysqli_stmt_execute($courseQuery);
    $courses = mysqli_stmt_get_result($courseQuery)->fetch_all(MYSQLI_ASSOC);
    mysqli_stmt_close($courseQuery);

    $todoQuery = mysqli_prepare($con, 'SELECT * FROM todo_items WHERE user_id = ? ORDER BY (deadline IS NULL), deadline, created_at DESC');
    mysqli_stmt_bind_param($todoQuery, 'i', $userId);
    mysqli_stmt_execute($todoQuery);
    $todoItems = mysqli_stmt_get_result($todoQuery)->fetch_all(MYSQLI_ASSOC);
    mysqli_stmt_close($todoQuery);

    $requestedMonth = (string) ($_GET['month'] ?? date('Y-m'));
    $calendarMonth = preg_match('/^\d{4}-\d{2}$/', $requestedMonth)
        ? new DateTimeImmutable($requestedMonth . '-01')
        : new DateTimeImmutable('first day of this month');
}

$flashError = get_flash('error');
$flashSuccess = get_flash('success');
if ($page === 'login' || $page === 'register') {
    if (!empty($_SESSION['user_id'])) {
        redirect_to('dashboard');
    }
    require __DIR__ . '/auth/' . $page . '.php';
    exit;
}
if ($page === '404') {
    echo '<!doctype html><html lang="id"><meta charset="utf-8"><title>Halaman tidak ditemukan</title><body><h1>404 - Halaman tidak ditemukan</h1><a href="' . e(app_url('dashboard')) . '">Kembali ke dashboard</a></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" dir="ltr" data-color-theme="Blue_Theme" class="light selected" data-layout="vertical"
    data-boxed-layout="boxed" data-card="shadow">
<head>
    <?php require __DIR__ . '/partials/head.php'; ?>
    <title><?= e(ucfirst(str_replace('-', ' ', $page))) ?> - Streaky</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="DEFAULT_THEME bg-white dark:bg-dark">
    <div id="main-wrapper" class="flex">
        <aside id="application-sidebar-brand"
            class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full xl:rtl:-translate-x-0 rtl:translate-x-full left-0 rtl:left-auto rtl:right-0 transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed top-0 with-vertical left-sidebar transition-all duration-300 h-screen z-20 flex-shrink-0 border-r rtl:border-l rtl:border-r-0 w-[270px] border-border dark:border-darkborder bg-white dark:bg-dark">
            <?php require __DIR__ . '/partials/sidebar.php'; ?>
        </aside>
        <div class="page-wrapper flex-1 xl:ms-[270px] overflow-x-hidden" role="main">
            <main class="max-w-full">
                <div class="container full-container py-5">
                    <?php if ($flashError): ?>
                        <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800"><?= e($flashError) ?></div>
                    <?php endif; ?>
                    <?php if ($flashSuccess): ?>
                        <div role="status" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-xs text-green-800"><?= e($flashSuccess) ?></div>
                    <?php endif; ?>
                    <?php require __DIR__ . '/' . $page . '.php'; ?>
                </div>
            </main>
        </div>
    </div>
    <script src="assets/js/vendor.min.js"></script>
    <?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
