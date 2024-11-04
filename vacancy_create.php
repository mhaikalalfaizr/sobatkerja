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

    if (empty($title) || empty($job_type) || empty($description) || empty($requirements) || empty($location)) {
        $errorMessage = "Semua kolom harus diisi.";
    } elseif ($vacancy->createVacancy($umkm_id, $title, $description, $requirements, $job_type, $location, $category)) {
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
<html lang="id">
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
    <form action="vacancy_create.php" method="POST" class="form-container" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="title">Judul Lowongan</label>
            <input type="text" id="title" name="title" placeholder="Misal : Waiter Gerai ABCD" required>
        </div>
        <div class="form-group">
            <label for="job_type">Jenis Pekerjaan</label>
            <select id="job_type" name="job_type" required>
                <option value="">--Pilih Jenis Pekerjaan--</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Freelance">Freelance (Remote)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi Pekerjaan</label>
            <textarea id="description" name="description" placeholder="Deskripsikan dengan detail, seperti lokasi, gaji, dll." required></textarea>
        </div>
        <div class="form-group"> 
            <label for="requirements">Persyaratan Pekerjaan</label>
            <textarea id="requirements" name="requirements" placeholder="Jelaskan dengan detail, seperti umur, jenis kelamin, riwayat, dll." required></textarea>
        </div>
        <div class="form-group">
            <label for="location">Lokasi</label>
            <select id="location" name="location" required>
                <option value="">--Pilih Lokasi--</option>
                <option value="Aceh">Aceh</option>
                <option value="Medan">Medan</option>
                <option value="Padang">Padang</option>
                <option value="Pekanbaru">Pekanbaru</option>
                <option value="Jambi">Jambi</option>
                <option value="Palembang">Palembang</option>
                <option value="Bengkulu">Bengkulu</option>
                <option value="Lampung">Lampung</option>
                <option value="Pangkal Pinang">Pangkal Pinang</option>
                <option value="Tanjung Pinang">Tanjung Pinang</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Semarang">Semarang</option>
                <option value="Yogyakarta">Yogyakarta</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Malang">Malang</option>
                <option value="Denpasar">Denpasar</option>
                <option value="Mataram">Mataram</option>
                <option value="Kupang">Kupang</option>
                <option value="Pontianak">Pontianak</option>
                <option value="Banjarmasin">Banjarmasin</option>
                <option value="Samarinda">Samarinda</option>
                <option value="Balikpapan">Balikpapan</option>
                <option value="Makassar">Makassar</option>
                <option value="Manado">Manado</option>
                <option value="Palu">Palu</option>
                <option value="Kendari">Kendari</option>
                <option value="Gorontalo">Gorontalo</option>
                <option value="Ambon">Ambon</option>
                <option value="Ternate">Ternate</option>
                <option value="Jayapura">Jayapura</option>
                <option value="Sorong">Sorong</option>
            </select>
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
