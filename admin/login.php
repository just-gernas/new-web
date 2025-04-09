<?php
require_once '../config.inc.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PW) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: header.inc.php'); // Replace with your admin landing page
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<form method="post" action="">
    <h2>Admin Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>
