<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

$user = new User();
$jobseeker_id = $_SESSION['user_id'];
$profile = $user->getProfile($jobseeker_id, 'JobSeeker');

if (!$profile) {
    $profile = [
        'full_name' => 'Data tidak tersedia',
        'job_field' => 'Data tidak tersedia',
        'skills' => 'Data tidak tersedia',
        'email' => 'Data tidak tersedia',
        'contact' => 'Data tidak tersedia'
    ];
}

$db = Database::getInstance()->getConnection();

$queryApplications = "
    SELECT 
        Vacancies.title AS vacancy_title,
        Applications.application_date,
        Applications.cv_path AS cv_link
    FROM Applications
    LEFT JOIN Vacancies ON Applications.vacancy_id = Vacancies.id
    WHERE Applications.jobseeker_id = ?
    ORDER BY Applications.application_date DESC
";
$stmt = $db->prepare($queryApplications);
$stmt->bind_param("i", $jobseeker_id);
$stmt->execute();
$applicationsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Job Seeker</title>
    <link rel="stylesheet" href="dashboard_jobseeker.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <div class="header-logo">
                <img src="assets/icon.svg" alt="Logo" class="logo-icon">
                <img src="assets/logotext.svg" alt="Text Logo" class="logo-text">
            </div>
            <div class="header-buttons">
                <a href="edit_profile_jobseeker.php" class="button">Edit Profil</a>
                <a href="vacancy_search.php" class="button">Cari Lowongan</a>
            </div>
        </header>

        <section class="profile-section">
            <h3>Profil Anda</h3>
            <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($profile['full_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>
            <p><strong>Kontak:</strong> <?= htmlspecialchars($profile['contact']) ?></p>
        </section>

        <section class="applications-section">
            <h3>Riwayat Aplikasi Lamaran</h3>
            <?php if ($applicationsResult->num_rows > 0): ?>
                <p>Anda akan dihubungi oleh pihak UMKM terkait lamaran anda secepatnya, harap menunggu ya :) </p>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Lowongan</th>
                            <th>Tanggal Melamar</th>
                            <th>CV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($application = $applicationsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($application['vacancy_title']) ?></td>
                                <td><?= htmlspecialchars($application['application_date']) ?></td>
                                <td>
                                    <?php if ($application['cv_link']): ?>
                                        <a href="<?= htmlspecialchars($application['cv_link']) ?>" target="_blank">Lihat CV</a>
                                    <?php else: ?>
                                        Tidak Ada CV
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada riwayat aplikasi lamaran.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

<?php
$stmt->close();
?>
