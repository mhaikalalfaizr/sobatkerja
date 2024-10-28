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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $job_type = $_POST['job_type'];
    $location = $_POST['location'];

    if ($vacancy->createVacancy($umkm_id, $title, $description, $requirements, $job_type, $location, $category)) {
        echo "<p class='success-message'>Lowongan berhasil ditambahkan!</p>";
    } else {
        echo "<p class='error-message'>Gagal menambahkan lowongan.</p>";
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
