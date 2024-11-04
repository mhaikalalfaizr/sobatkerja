<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

$user = new User();
$errorMessage = '';
$successMessage = '';

$email = $_SESSION['reset_email'] ?? $_SESSION['user_email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $errorMessage = "Password tidak sesuai.";
    } elseif (empty($email)) {
        $errorMessage = "Sesi telah usai, silahkan ulangi prosesnya.";
    } else {
        if ($user->resetPassword($email, $newPassword)) {
            $successMessage = "Password berhasil diubah!";
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Password berhasil diubah! Mengalihkan ke laman login...', 'success');
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 2000);
                    });
                  </script>";
            unset($_SESSION['reset_email']);
            unset($_SESSION['user_email']);
        } else {
            $errorMessage = "Gagal mengubah password. Silahkan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="password.css">
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
    <h1>Reset Password</h1>
    <form action="password_reset.php" method="post">
        <input type="password" name="new_password" placeholder="Password baru" required>
        <input type="password" name="confirm_password" placeholder="Konfirmasi password baru" required>
        <button type="submit">Reset Password</button>
    </form>
</div>
<?php if ($errorMessage): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification("<?= $errorMessage ?>", "error");
        });
    </script>
<?php endif; ?>
</body>
</html>
