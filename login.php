<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userType = $_POST["userType"]; // UMKM atau JobSeeker

    $user = new User();
    if ($user->login($email, $password, $userType)) {
        // Redirect ke dashboard sesuai userType
        if ($userType == "UMKM") {
            header('Location: dashboard_umkm.php');
        } else {
            header('Location: dashboard_jobseeker.php');
        }
        exit();
    } else {
        $error = "Email atau password atau jenis pengguna salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SobatKerja - Login</title>
    <link rel="stylesheet" href="daftar.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/icon.svg" alt="Logo" class="logo-icon">
            <img src="assets/logotext.svg" alt="Text Logo" class="logo-text">
        </div>
        <h1>Login</h1>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="post" action="">
            <label for="email">Email atau Nomor Kontak:</label>
            <input type="text" id="email" name="email" placeholder="contoh@email.com atau 62812xxxxxx" required>

            <label for="password">Kata Sandi:</label>
            <input type="password" id="password" name="password" placeholder="********" required>

            <label for="userType">Jenis Pengguna:</label>
            <select id="userType" name="userType">
                <option value="UMKM">UMKM</option>
                <option value="JobSeeker">Pencari Kerja</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
