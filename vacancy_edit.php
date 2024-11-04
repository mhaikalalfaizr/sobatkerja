<?php
session_start();
require_once 'Database.php';
require_once 'Vacancy.php';

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

$query = "SELECT title, description, requirements, job_type, location, category FROM Vacancies WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $vacancy_id);
$stmt->execute();
$result = $stmt->get_result();
$vacancy = $result->fetch_assoc();

if (!$vacancy) {
    echo "Lowongan tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $job_type = $_POST['job_type'];
    $location = $_POST['location'];

    $updateQuery = "UPDATE Vacancies SET title = ?, description = ?, requirements = ?, job_type = ?, location = ? WHERE id = ?";
    $stmt = $db->prepare($updateQuery);
    $stmt->bind_param("sssssi", $title, $description, $requirements, $job_type, $location, $vacancy_id);
    
    if ($stmt->execute()) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            showNotification('Perubahan berhasil disimpan!', 'success');
            setTimeout(function() { window.location.href = 'dashboard_umkm.php?vacancy_id={$vacancy_id}'; }, 2000);
        });</script>";
    } else {
        echo "<p class='error-message'>Gagal menyimpan perubahan. Silakan coba lagi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lowongan</title>
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
        .notification.success { background-color: #4CAF50; }
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
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Lowongan</h2>
        <form action="vacancy_edit.php?vacancy_id=<?= htmlspecialchars($vacancy_id) ?>" method="POST">
            <div class="form-group">
                <label for="title">Judul Lowongan</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($vacancy['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="job_type">Jenis Pekerjaan</label>
                <select id="job_type" name="job_type" required>
                    <option value="Full-time" <?= $vacancy['job_type'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                    <option value="Part-time" <?= $vacancy['job_type'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                    <option value="Freelance" <?= $vacancy['job_type'] == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Pekerjaan</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($vacancy['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="requirements">Persyaratan Pekerjaan</label>
                <textarea id="requirements" name="requirements" required><?= htmlspecialchars($vacancy['requirements']) ?></textarea>
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
            <button type="submit" class="submit-button">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>