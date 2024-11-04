<?php
session_start();
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST["identifier"]; 
    $password = $_POST["password"];
    $userType = $_POST["userType"]; 

    if ($user->login($identifier, $password, $userType)) {
        $_SESSION['user_email'] = $identifier;
        $dashboardUrl = $userType == "UMKM" ? 'dashboard_umkm.php' : 'dashboard_jobseeker.php';
    
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Login berhasil! Mengalihkan anda ke dashboard...', 'success');
                setTimeout(function() {
                    window.location.href = '$dashboardUrl';
                }, 2000);
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Email atau password atau jenis pengguna salah.', 'error');
            });
        </script>";
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
    <link rel="stylesheet" href="notification.css">
    <script>
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = 'notification ' + type;
            notification.innerText = message;
            document.body.appendChild(notification);
            setTimeout(function () {
                notification.classList.add('show');
            }, 100);
            setTimeout(function () {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/icon.svg" alt="Logo" id="icon-logo">
            <img src="assets/logotext.svg" alt="Text Logo" id="text-logo">
        </div>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="identifier">Email atau Nomor Kontak:</label>
            <input type="text" id="identifier" name="identifier" placeholder="contoh@email.com atau 62812xxxxxx" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="******" required>

            <label for="userType">Jenis Pengguna:</label>
            <select id="userType" name="userType" required>
                <option value="UMKM">UMKM</option>
                <option value="JobSeeker">Pencari Kerja</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p>Lupa password? <a href="password_forgot.php">Reset di sini</a></p>

        <p>Belum memiliki akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
