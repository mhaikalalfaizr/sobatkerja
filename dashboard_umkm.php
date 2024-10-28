<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

$user = new User();
$umkm_id = $_SESSION['user_id'];
$profile = $user->getProfile($umkm_id, 'UMKM');

$db = Database::getInstance()->getConnection();

$queryVacancies = "
    SELECT 
        Vacancies.id AS vacancy_id,
        Vacancies.title AS vacancy_title,
        COUNT(Applications.id) AS applicant_count
    FROM Vacancies
    LEFT JOIN Applications ON Vacancies.id = Applications.vacancy_id
    WHERE Vacancies.umkm_id = ?
    GROUP BY Vacancies.id
";
$stmt = $db->prepare($queryVacancies);
$stmt->bind_param("i", $umkm_id);
$stmt->execute();
$vacanciesResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard UMKM</title>
    <link rel="stylesheet" href="dashboard_umkm.css">
    <script>
        function confirmDelete(vacancyId) {
            if (confirm("Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dikembalikan.")) {
                window.location.href = 'vacancy_delete.php?vacancy_id=' + vacancyId;
            }
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <div class="header-logo">
                <img src="assets/icon.svg" alt="Logo" class="logo-icon">
                <img src="assets/logotext.svg" alt="Text Logo" class="logo-text">
            </div>
            <h2>Dashboard UMKM</h2>
            <div class="header-buttons">
                <a href="edit_profile_umkm.php" class="button">Edit Profil</a>
                <a href="vacancy_create.php" class="button">Tambah Lowongan</a>
            </div>
        </header>

        <section class="profile-section">
            <h3>Profil Usaha Anda</h3>
            <p><strong>Nama Usaha:</strong> <?= htmlspecialchars($profile['business_name']) ?></p>
            <p><strong>Jenis Usaha:</strong> <?= htmlspecialchars($profile['business_type']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($profile['address']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>
            <p><strong>Kontak:</strong> <?= htmlspecialchars($profile['contact']) ?></p>
        </section>

        <section class="vacancies-section">
            <h3>Daftar Lowongan</h3>
            <?php if ($vacanciesResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Lowongan</th>
                            <th>Jumlah Pelamar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($vacancy = $vacanciesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($vacancy['vacancy_title']) ?></td>
                                <td><?= htmlspecialchars($vacancy['applicant_count'] ?? 0) ?></td>
                                <td>
                                    <a href="vacancy_details.php?vacancy_id=<?= $vacancy['vacancy_id'] ?>">Lihat Detail</a> |
                                    <a href="vacancy_edit.php?vacancy_id=<?= $vacancy['vacancy_id'] ?>">Edit</a> |
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $vacancy['vacancy_id'] ?>)">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada lowongan yang tersedia.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

<?php
$stmt->close();
?>