<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User();

    if ($user->passwordVerify($email, $password)) {
        header('Location: password_reset.php');
        exit();
    } else {
        $errorMessage = "Verifikasi gagal.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Identity</title>
    <link rel="stylesheet" href="password.css">
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="assets/icon.svg" alt="Logo" id="icon-logo">
        <img src="assets/logotext.svg" alt="Text Logo" id="text-logo">
    </div>
    <h1>Verifikasi Identitas</h1>
    <form action="password_verify.php" method="post">
        <input type="email" name="email" placeholder="Email anda" required>
        <input type="password" name="password" placeholder="Password saat ini" required>
        <button type="submit">Verify</button>
    </form>
    <p>Back to <a href="login.php">Login</a></p>
</div>
</body>
</html>
