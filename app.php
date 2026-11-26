<?php
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_url($page = 'dashboard')
{
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $base = rtrim($script, '/');
    return ($base === '' ? '' : $base) . '/index.php?page=' . rawurlencode($page);
}

function ensure_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    echo '<input type="hidden" name="csrf_token" value="' . e(ensure_csrf_token()) . '">';
}

function set_flash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key)
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

function redirect_to($page)
{
    header('Location: ' . app_url($page));
    exit;
}

function validate_csrf()
{
    $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function insert_courses($con, $userId, $courses)
{
    $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $insert = mysqli_prepare(
        $con,
        'INSERT INTO courses (user_id, name, sks, day, time, room) VALUES (?, ?, ?, ?, ?, ?)'
    );

    foreach ($courses as $course) {
        if (!is_array($course)) {
            continue;
        }

        $name = trim((string) ($course['nama'] ?? ''));
        if ($name === '') {
            continue;
        }

        $sks = filter_var($course['sks'] ?? 0, FILTER_VALIDATE_INT);
        if ($sks === false || $sks < 0 || $sks > 10) {
            throw new InvalidArgumentException('SKS harus berupa angka antara 0 dan 10.');
        }

        $day = trim((string) ($course['hari'] ?? ''));
        if ($day !== '' && !in_array($day, $allowedDays, true)) {
            throw new InvalidArgumentException('Hari kuliah tidak valid.');
        }

        $time = trim((string) ($course['jam'] ?? ''));
        $room = trim((string) ($course['ruangan'] ?? ''));
        mysqli_stmt_bind_param($insert, 'isisss', $userId, $name, $sks, $day, $time, $room);
        mysqli_stmt_execute($insert);
    }

    mysqli_stmt_close($insert);
}

function handle_request($con)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && empty($_GET['action'])) {
        return;
    }

    $action = (string) ($_POST['action'] ?? $_GET['action'] ?? '');
    if ($action === '') {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validate_csrf()) {
        http_response_code(400);
        set_flash('error', 'Permintaan tidak valid atau sesi formulir sudah kedaluwarsa. Silakan coba lagi.');
        redirect_to($_GET['page'] ?? 'login');
    }

    if ($action === 'register') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            set_flash('error', 'Isi nama dan email dengan benar. Password minimal 8 karakter.');
            redirect_to('register');
        }
        if ($password !== $confirmation) {
            set_flash('error', 'Konfirmasi password tidak sama.');
            redirect_to('register');
        }

        $check = mysqli_prepare($con, 'SELECT id FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($check, 's', $email);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check)->fetch_assoc();
        mysqli_stmt_close($check);
        if ($exists) {
            set_flash('error', 'Email tersebut sudah terdaftar.');
            redirect_to('register');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = mysqli_prepare($con, 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($insert, 'sss', $name, $email, $hash);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
        set_flash('success', 'Akun berhasil dibuat. Silakan masuk.');
        redirect_to('login');
    }

    if ($action === 'login') {
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $query = mysqli_prepare($con, 'SELECT id, password FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($query, 's', $email);
        mysqli_stmt_execute($query);
        $account = mysqli_stmt_get_result($query)->fetch_assoc();
        mysqli_stmt_close($query);

        if (!$account || !password_verify($password, $account['password'])) {
            set_flash('error', 'Email atau password salah.');
            redirect_to('login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $account['id'];
        ensure_csrf_token();
        redirect_to('dashboard');
    }

    if ($action === 'logout') {
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        redirect_to('login');
    }

    if (empty($_SESSION['user_id'])) {
        redirect_to('login');
    }
    $userId = (int) $_SESSION['user_id'];

    if ($action === 'save_frs' || $action === 'save_settings') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $program = trim((string) ($_POST['prodi'] ?? ''));
        $semester = trim((string) ($_POST['semester'] ?? ''));
        $courses = $_POST['matkul'] ?? [];

        if ($action === 'save_settings') {
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $program === '' || $semester === '') {
                set_flash('error', 'Lengkapi nama, email, program studi, dan semester dengan benar.');
                redirect_to('settings');
            }
            $avatarPath = null;
            if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $upload = $_FILES['avatar'];
                $imageInfo = $upload['error'] === UPLOAD_ERR_OK ? getimagesize($upload['tmp_name']) : false;
                $mime = $imageInfo['mime'] ?? '';
                $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                if (!$imageInfo || !isset($extensions[$mime]) || $upload['size'] > 2 * 1024 * 1024) {
                    set_flash('error', 'Foto harus berupa JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.');
                    redirect_to('settings');
                }
                $directory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                    throw new RuntimeException('Folder upload tidak dapat dibuat.');
                }
                $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
                if (!move_uploaded_file($upload['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
                    throw new RuntimeException('Foto profil gagal disimpan.');
                }
                $avatarPath = 'uploads/' . $filename;
            }

            mysqli_begin_transaction($con);
            try {
                if ($avatarPath !== null) {
                    $update = mysqli_prepare($con, 'UPDATE users SET name = ?, email = ?, prodi = ?, semester = ?, avatar_path = ? WHERE id = ?');
                    mysqli_stmt_bind_param($update, 'sssssi', $name, $email, $program, $semester, $avatarPath, $userId);
                } else {
                    $update = mysqli_prepare($con, 'UPDATE users SET name = ?, email = ?, prodi = ?, semester = ? WHERE id = ?');
                    mysqli_stmt_bind_param($update, 'ssssi', $name, $email, $program, $semester, $userId);
                }
                mysqli_stmt_execute($update);
                mysqli_stmt_close($update);

                mysqli_query($con, 'DELETE FROM courses WHERE user_id = ' . $userId);
                insert_courses($con, $userId, $courses);
                mysqli_commit($con);
            } catch (Throwable $error) {
                mysqli_rollback($con);
                throw $error;
            }
            set_flash('success', 'Pengaturan berhasil disimpan.');
            redirect_to('settings');
        }

        if ($program === '' || $semester === '' || !is_array($courses)) {
            set_flash('error', 'Lengkapi program studi, semester, dan daftar mata kuliah.');
            redirect_to('add-frs');
        }

        mysqli_begin_transaction($con);
        try {
            $update = mysqli_prepare($con, 'UPDATE users SET prodi = ?, semester = ? WHERE id = ?');
            mysqli_stmt_bind_param($update, 'ssi', $program, $semester, $userId);
            mysqli_stmt_execute($update);
            mysqli_stmt_close($update);
            insert_courses($con, $userId, $courses);
            mysqli_commit($con);
        } catch (Throwable $error) {
            mysqli_rollback($con);
            throw $error;
        }
        set_flash('success', 'Data FRS berhasil disimpan.');
        redirect_to('dashboard');
    }

    if ($action === 'create_todo') {
        $title = trim((string) ($_POST['title'] ?? ''));
        $courseName = trim((string) ($_POST['course_name'] ?? ''));
        $type = (($_POST['type'] ?? 'task') === 'study') ? 'study' : 'task';
        $priority = (string) ($_POST['priority'] ?? 'Sedang');
        $deadlineInput = trim((string) ($_POST['deadline'] ?? ''));

        if ($title === '' || !in_array($priority, ['Tinggi', 'Sedang', 'Rendah'], true)) {
            set_flash('error', 'Judul tugas dan prioritas harus diisi dengan benar.');
            redirect_to($_POST['return_page'] ?? 'todo-list');
        }
        $deadline = null;
        if ($deadlineInput !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $deadlineInput);
            if (!$date || $date->format('Y-m-d') !== $deadlineInput) {
                set_flash('error', 'Tanggal deadline tidak valid.');
                redirect_to($_POST['return_page'] ?? 'todo-list');
            }
            $deadline = $date->format('Y-m-d 00:00:00');
        }
        if ($type === 'study') {
            $priority = null;
        }

        $insert = mysqli_prepare($con, 'INSERT INTO todo_items (user_id, type, title, course_name, deadline, priority) VALUES (?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($insert, 'isssss', $userId, $type, $title, $courseName, $deadline, $priority);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
        set_flash('success', 'Aktivitas berhasil ditambahkan.');
        redirect_to($_POST['return_page'] ?? 'todo-list');
    }

    if ($action === 'toggle_todo') {
        $todoId = filter_var($_POST['todo_id'] ?? 0, FILTER_VALIDATE_INT);
        if (!$todoId) {
            http_response_code(400);
            exit('ID aktivitas tidak valid.');
        }
        $query = mysqli_prepare($con, 'SELECT completed_at FROM todo_items WHERE id = ? AND user_id = ? LIMIT 1');
        mysqli_stmt_bind_param($query, 'ii', $todoId, $userId);
        mysqli_stmt_execute($query);
        $todo = mysqli_stmt_get_result($query)->fetch_assoc();
        mysqli_stmt_close($query);
        if (!$todo) {
            http_response_code(404);
            exit('Aktivitas tidak ditemukan.');
        }
        $completedAt = $todo['completed_at'] === null ? date('Y-m-d H:i:s') : null;
        $update = mysqli_prepare($con, 'UPDATE todo_items SET completed_at = ? WHERE id = ? AND user_id = ?');
        mysqli_stmt_bind_param($update, 'sii', $completedAt, $todoId, $userId);
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
        if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true]);
            exit;
        }
        redirect_to($_POST['return_page'] ?? 'todo-list');
    }

    http_response_code(404);
    exit('Aksi tidak dikenali.');
}

function indonesian_date($value, $withTime = false)
{
    if (!$value) {
        return '';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '';
    }

    $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];
    $formatted = $days[(int) date('w', $timestamp)] . ' ' . date('d', $timestamp) . ' '
        . $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    return $withTime ? $formatted . ', ' . date('H.i', $timestamp) : $formatted;
}

function streak_metrics($items)
{
    $dates = [];
    foreach ($items as $item) {
        if (!empty($item['completed_at'])) {
            $dates[substr($item['completed_at'], 0, 10)] = true;
        }
    }
    $dates = array_keys($dates);
    sort($dates);
    $dateSet = array_fill_keys($dates, true);

    $best = 0;
    $run = 0;
    $previous = null;
    foreach ($dates as $date) {
        $timestamp = strtotime($date);
        $run = $previous !== null && $timestamp - strtotime($previous) === 86400 ? $run + 1 : 1;
        $best = max($best, $run);
        $previous = $date;
    }

    $current = 0;
    $today = new DateTimeImmutable('today');
    while (isset($dateSet[$today->format('Y-m-d')])) {
        $current++;
        $today = $today->modify('-1 day');
    }
    return ['current' => $current, 'best' => $best, 'dates' => $dates];
}
