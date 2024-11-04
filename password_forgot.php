<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

$user = new User();
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $question = $_POST['security_question'];
    $answer = $_POST['security_answer'];

    if ($user->verifySecurityQuestion($email, $question, $answer)) {
        $_SESSION['reset_email'] = $email;
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Verifikasi sukses! Mengalihkan...', 'success');
                    setTimeout(function() {
                        window.location.href = 'password_reset.php';
                    }, 2000);
                });
              </script>";
    } else {
        $errorMessage = "Jawaban pertanyaan keamanan anda salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="password.css">
    <script>
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerText = message;

            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
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
    <h1>Lupa Password</h1>
    <form action="password_forgot.php" method="post">
        <input type="email" name="email" placeholder="Email anda" required>
        <select name="security_question" required>
            <option value="">--Pilih Pertanyaan Keamanan--</option>
            <option value="Nama hewan peliharaan pertama Anda?">Nama hewan peliharaan pertama Anda?</option>
            <option value="Nama sekolah pertama Anda?">Nama sekolah pertama Anda?</option>
            <option value="Nama kota tempat Anda dilahirkan?">Nama kota tempat Anda dilahirkan?</option>
        </select>
        <input type="text" name="security_answer" placeholder="Jawaban" required>
        <button type="submit">Verifikasi</button>
    </form>
    <p>Kembali ke <a href="login.php">halaman login</a></p>
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