<?php
$con = mysqli_connect('localhost', 'root', '', 'streaky');
if (!$con) {
    throw new RuntimeException('Koneksi database gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($con, 'utf8mb4');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$schema = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        program_studi VARCHAR(190) NOT NULL DEFAULT '',
        semester VARCHAR(120) NOT NULL DEFAULT '',
        avatar_path VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS courses (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        name VARCHAR(190) NOT NULL,
        sks TINYINT UNSIGNED NOT NULL DEFAULT 0,
        day VARCHAR(20) NOT NULL DEFAULT '',
        time VARCHAR(60) NOT NULL DEFAULT '',
        room VARCHAR(100) NOT NULL DEFAULT '',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX courses_user_id_idx (user_id),
        CONSTRAINT courses_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS todo_items (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        type ENUM('task', 'study') NOT NULL DEFAULT 'task',
        title VARCHAR(255) NOT NULL,
        course_name VARCHAR(190) NOT NULL DEFAULT '',
        deadline DATETIME DEFAULT NULL,
        priority ENUM('Tinggi', 'Sedang', 'Rendah') DEFAULT 'Sedang',
        completed_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX todo_user_deadline_idx (user_id, deadline),
        CONSTRAINT todo_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

foreach ($schema as $statement) {
    mysqli_query($con, $statement);
}
