<?php session_start(); ?>
<?php include "../koneksi.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD authentication page">
  <title>Login | FLORIST.</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-body">
  <main class="auth-page">
    <section class="auth-card">
      <form method="POST">
        <div class="mb-4">
          <h1 class="h3 mb-1">Login</h1>
          <p class="text-muted mb-0">Sign in to your admin workspace.</p>
        </div>
        <div class="mb-3"><label class="form-label" for="">Username</label>
          <input class="form-control" id="" name="username" type="text" required>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between"><label class="form-label" for="loginPassword">Password</label>
          </div>
          <input class="form-control" id="" type="password" name="password" required>
        </div>
        <button class="btn btn-primary w-100" type="submit" name="login">
          <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
          Sign In</button>
      </form>
      <?php

      if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if ($password == $row['password']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['status'] = 'login';
            $_SESSION['users'] = $row;
            header("Location: index.php");
            exit;
          } else {
            echo "<script>alert('Password salah');document.location.href='login.php';</script>";
          }
        } else {
          echo "<script>alert('Username tidak ditemukan');document.location.href='login.php';</script>";
        }
      }

      ?>

    </section>
  </main>
</body>

</html>