<?php
session_start();
session_destroy();
?>

<script>
localStorage.removeItem('remember_email');
localStorage.removeItem('remember_password');
window.location.href = "login.php";
</script>