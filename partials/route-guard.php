<?php
if (!defined('APP_BOOTSTRAPPED')) {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $directory = str_replace('\\', '/', dirname($script));
    if (basename($directory) === 'auth') {
        $directory = str_replace('\\', '/', dirname($directory));
    }

    $base = rtrim($directory, '/');
    $page = pathinfo($script, PATHINFO_FILENAME);
    header('Location: ' . ($base === '' ? '' : $base) . '/index.php?page=' . rawurlencode($page));
    exit;
}
