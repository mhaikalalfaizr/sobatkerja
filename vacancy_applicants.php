<?php
session_start();
require_once 'Application.php';
require_once 'Vacancy.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

$vacancy_id = $_GET['vacancy_id'] ?? 0;
$application = new Application();
$vacancy = new Vacancy();

$umkm_id = $_SESSION['user_id'];
$vacancyDetails = $vacancy->getVacancyDetails($vacancy_id);

if (!$vacancyDetails || $vacancyDetails['umkm_id'] !== $umkm_id) {
    echo "Lowongan tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}

$applicants = $application->getApplicants($vacancy_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = $_POST['application_id'];
    $status = $_POST['status'];
    $application->updateApplicationStatus($applicationId, $status);
    header("Location: vacancy_applicants.php?vacancy_id=$vacancy_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelamar</title>
    <link rel="stylesheet" href="vacancy_applicant.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Pelamar untuk Lowongan: <?php echo htmlspecialchars($vacancyDetails['title']); ?></h2>
        <table class="applicant-table">
            <thead>
                <tr>
                    <th>Nama Pelamar</th>
                    <th>Tanggal Melamar</th>
                    <th>Unduh CV</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applicants)): ?>
                    <?php foreach ($applicants as $applicant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($applicant['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['application_date']); ?></td>
                            <td>
                            <a href="<?php echo htmlspecialchars($applicant['cv_path']); ?>" target="_blank" class="download-button">Unduh CV</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Belum ada pelamar untuk lowongan ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
