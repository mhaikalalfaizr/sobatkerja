<?php
session_start();
require_once 'User.php';
require_once 'Vacancy.php';

// Cek apakah pengguna adalah UMKM
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

// Inisialisasi objek
$user = new User();
$vacancy = new Vacancy();

// Ambil informasi profil UMKM
$umkm_id = $_SESSION['user_id'];
$profile = $user->getProfile($umkm_id, 'UMKM');

// Ambil daftar lowongan yang dibuat oleh UMKM ini
$vacancies = $vacancy->getVacancies($umkm_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UMKM</title>
    <link rel="stylesheet" href="dashboard_umkm.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <div class="logo">
                <img src="assets/icon.svg" alt="Logo" class="logo-icon">
                <img src="assets/logotext.svg" alt="Text Logo" class="logo-text">
            </div>
            <h2>Dashboard UMKM</h2>
        </header>

        <section class="profile-section">
            <h3>Profil UMKM</h3>
            <p><strong>Nama Usaha:</strong> <?php echo htmlspecialchars($profile['business_name']); ?></p>
            <p><strong>Jenis Usaha:</strong> <?php echo htmlspecialchars($profile['business_type']); ?></p>
            <p><strong>Alamat:</strong> <?php echo htmlspecialchars($profile['address']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
            <p><strong>Kontak:</strong> <?php echo htmlspecialchars($profile['contact']); ?></p>
            <a href="edit_profile_umkm.php" class="button">Edit Profil</a>
        </section>

        <div class="add-vacancy-button">
            <a href="vacancy_create.php" class="button">Tambah Lowongan Baru</a>
        </div>

        <section class="view-vacancies">
            <h3>Status Lowongan dan Pelamar</h3>
            <table>
                <thead>
                    <tr>
                        <th>Judul Lowongan</th>
                        <th>Jumlah Pelamar</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vacancies as $vacancy): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vacancy['title']); ?></td>
                            <td><?php echo htmlspecialchars($vacancy['applicant_count'] ?? 0); ?></td>
                            <td><a href="vacancy_applicants.php?vacancy_id=<?php echo $vacancy['id']; ?>">Lihat Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
