<?php
session_start();
require_once 'Vacancy.php';
require_once 'User.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

$vacancy = new Vacancy();
$user = new User();

$umkm_id = $_SESSION['user_id'];
$profile = $user->getProfile($umkm_id, 'UMKM');
$category = $profile['business_type'];

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $job_type = $_POST['job_type'];
    $location = $_POST['location'];

    if ($vacancy->createVacancy($umkm_id, $title, $description, $requirements, $job_type, $location, $category)) {
        $successMessage = 'Lowongan berhasil ditambahkan! Mengalihkan kembali ke dashboard...';
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('$successMessage', 'success');
                    setTimeout(function() { window.location.href = 'dashboard_umkm.php'; }, 2000);
                });
              </script>";
    } else {
        $errorMessage = 'Gagal menambahkan lowongan.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Lowongan Baru</title>
    <link rel="stylesheet" href="vacancy_create.css">
    <style>
        .notification {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 15px;
            position: fixed;
            z-index: 1;
            bottom: 30px;
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
        .notification.success { background-color: #4CAF50; }
        .notification.error { background-color: #f44336; }
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
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($errorMessage): ?>
                showNotification("<?= $errorMessage ?>", "error");
            <?php endif; ?>
        });
    </script>
</head>
<body>
<div class="container">
    <h2>Buat Lowongan Baru</h2>
    <form action="vacancy_create.php" method="POST" class="form-container">
        <div class="form-group">
            <label for="title">Judul Lowongan</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="job_type">Jenis Pekerjaan</label>
            <select id="job_type" name="job_type" required>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Freelance">Freelance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi Pekerjaan</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group"> 
            <label for="requirements">Persyaratan Pekerjaan</label>
            <textarea id="requirements" name="requirements" required></textarea>
        </div>
        <div class="form-group">
            <label for="location">Lokasi</label>
            <input type="text" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="category">Kategori Usaha</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>" readonly>
        </div>
        <button type="submit" class="submit-button">Tambahkan Lowongan</button>
    </form>
</div>
</body>
</html>