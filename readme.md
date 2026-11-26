# Nama Proyek

Deskripsi singkat mengenai fungsi utama website atau aplikasi PHP native ini.

## Prasyarat

* [Git](https://git-scm.com/downloads) (untuk melakukan *clone* repositori)
* Browser web (Chrome, Firefox, Edge, dll.)

## Struktur Folder
```text
nama-repo/
├── assets/         # File CSS, JavaScript, dan gambar
├── config/         # Konfigurasi koneksi database
├── database/       # File dump database (.sql)
├── pages/          # Halaman-halaman pendukung aplikasi
└── index.php       # Halaman utama
```

## Cara Instalasi & Setup Lokal (Dari Awal)

### 1. Download dan Instal XAMPP
1. Unduh installer XAMPP melalui situs resmi [Apache Friends](https://www.apachefriends.org/download.html). Pilih versi PHP yang sesuai (disarankan **PHP 8.x**).
2. Jalankan file installer yang sudah diunduh.
3. Pada bagian **Select Components**, pastikan komponen **Apache**, **MySQL**, **PHP**, dan **phpMyAdmin** tercentang, lalu klik **Next**.
4. Biarkan folder instalasi di lokasi bawaan (misalnya `C:\xampp` untuk Windows), lalu klik **Next** hingga proses instalasi selesai.

### 2. Jalankan Layanan XAMPP
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada baris **Apache** dan **MySQL** hingga indikator modul berubah menjadi warna hijau.

### 3. Clone Repositori ke Folder `htdocs`
Buka terminal atau Git Bash, lalu masuk ke direktori `htdocs` di dalam folder instalasi XAMPP Anda:

* **Windows:** `cd C:\xampp\htdocs`
* **macOS:** `cd /Applications/XAMPP/htdocs`
* **Linux:** `cd /opt/lampp/htdocs`

Unduh kode sumber proyek dengan perintah:
```bash
git clone https://github.com/jessicamanda/streaky.git
```

### 4. Impor Database
1. Buka browser dan akses **phpMyAdmin** melalui alamat `http://localhost/phpmyadmin`.
2. Klik tab **Databases** (Basis Data), isi nama database dengan `streaky`, lalu klik **Create**.
3. Klik nama database `streaky` di panel sebelah kiri, lalu buka tab **Import**.
4. Klik **Choose File** dan pilih file `.sql` yang ada di dalam folder proyek (misal: `C:\xampp\htdocs\streaky\streaky.sql`).
5. Gulir ke bawah dan klik **Import** (atau **Go**).

### 5. Konfigurasi Koneksi Database
1. Buka folder proyek di editor kode (VS Code, Sublime Text, dll.).
2. Buka file konfigurasi database (misal: `config/koneksi.php`). Jika hanya tersedia file contoh (`config/koneksi.example.php`), salin dan ubah namanya menjadi `koneksi.php`.
3. Pastikan pengaturan koneksi sesuai dengan konfigurasi bawaan XAMPP:

$host = "localhost";
$user = "root";
$pass = "";
$db   = "streaky";

### 6. Jalankan Proyek
Buka browser Anda dan akses aplikasi melalui URL berikut:
```text
http://localhost/streaky
```