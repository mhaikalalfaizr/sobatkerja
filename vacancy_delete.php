<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['vacancy_id'])) {
    echo "Lowongan tidak ditemukan.";
    exit();
}

$vacancy_id = $_GET['vacancy_id'];
$db = Database::getInstance()->getConnection();

$deleteQuery = "DELETE FROM Vacancies WHERE id = ?";
$stmt = $db->prepare($deleteQuery);
$stmt->bind_param("i", $vacancy_id);

if ($stmt->execute()) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() {
        showNotification('Lowongan berhasil dihapus!', 'success');
        setTimeout(function() { window.location.href = 'dashboard_umkm.php'; }, 2000);
    });</script>";
} else {
    echo "<p class='error-message'>Gagal menghapus lowongan. Silakan coba lagi.</p>";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus Lowongan</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .notification {
            visibility: hidden;
            min-width: 250px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 15px;
            position: fixed;
            z-index: 1;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 16px;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s ease-in-out;
        }
        .notification.show {
            visibility: visible;
            opacity: 1;
        }
        .notification.success { background-color: #f44336; } /* Warna merah untuk penghapusan */
    </style>
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
        }
    </script>
</head>
<body>
</body>
</html>
