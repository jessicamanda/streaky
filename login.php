<?php session_start(); ?>
<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="admin/assets/img/apple-icon.png">
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23ffffff' class='bi bi-leaf' viewBox='0 0 16 16'%3E%3Cpath d='M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z'/%3E%3C/svg%3E">
    <title>
        <?= $appname ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/public/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/public/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="admin/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
    </style>
</head>

<body>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-xl-4 col-lg-4 col-md-6 d-flex flex-column mx-auto">
                <div class="card">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h4 class="font-weight-bolder text-primary text-gradient">Haloo! Selamat Datang</h4>
                        <p class="mb-0" style="font-size: 14px;">Yuk, masukkan username dan password untuk masuk ke akun Anda</p>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <label>Username</label>
                            <div class="mb-2">
                                <input type="username" name="username" class="form-control" placeholder="Username">
                            </div>
                            <label>Password</label>
                            <div class="mb-2">
                                <div style="position: relative;">
                                    <input type="password" name="password" class="form-control" placeholder="Password" id="password">
                                    <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="login" class="btn bg-gradient-primary w-100 mt-4 mb-0">Masuk</button>
                            </div>
                        </form>
                        <?php

                        if (isset($_POST['login'])) {
                            $username = $_POST['username'];
                            $password = $_POST['password'];

                            $sql = "SELECT * FROM admin WHERE username = ?";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("s", $username);
                            $stmt->execute();

                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                if ($password == $row['password']) {
                                    $_SESSION['username'] = $row['username'];
                                    $_SESSION['admin'] = $row;
                                    header("Location: admin/index.php");
                                    exit;
                                } else {
                                    echo "<script>alert('Password salah');document.location.href='login.php';</script>";
                                }
                            } else {
                                echo "<script>alert('Username tidak ditemukan');document.location.href='login.php';</script>";
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="admin/assets/js/core/popper.min.js"></script>
    <script src="admin/assets/js/core/bootstrap.min.js"></script>
    <script src="admin/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="admin/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="admin/assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
</body>
<style>
    .bg-gradient-primary {
        background-image: linear-gradient(310deg, #67d132 0%, rgb(103, 209, 50)100%);
    }

    body {
        overflow-y: hidden;
        overflow-x: hidden;
        background-image: url('https://png.pngtree.com/thumb_back/fw800/background/20220312/pngtree-top-view-of-fruit-and-vegetable-collection-image_1066077.jpg');
        background-size: cover;
        background-repeat: no-repeat;

    }

    .text-gradient.text-primary {
        background-image: linear-gradient(310deg, #67d132 0%, rgb(103, 209, 50)100%);
    }

    .text-primary {
        color: #67d132 !important;
    }

    .card {
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: #67d132;
        z-index: 0;
    }

    body>* {
        position: relative;
        z-index: 2;
    }
</style>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Optional: ganti icon-nya juga
        togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>

</html>