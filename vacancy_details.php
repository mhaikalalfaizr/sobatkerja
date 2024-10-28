<?php
session_start();
require_once 'Database.php';
require_once 'Vacancy.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

$vacancy = new Vacancy();
$vacancy_id = $_GET['vacancy_id'];
$vacancyDetails = $vacancy->getVacancyDetails($vacancy_id);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Detail Lowongan</title>
        <link rel="stylesheet" href="styles.css">
    </head>
        <body>
            <div class="container">
                <section class="vacancy-details">
                    <h3>Detail Lowongan</h3>
                        <p><strong>Judul:</strong> <?= htmlspecialchars($vacancyDetails['title']) ?></p>
                        <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($vacancyDetails['job_type']) ?></p>
                        <p><strong>Deskripsi:</strong> <?= htmlspecialchars($vacancyDetails['description']) ?></p>
                        <p><strong>Persyaratan:</strong> <?= htmlspecialchars($vacancyDetails['requirements']) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($vacancyDetails['location']) ?></p>
                    <a href="vacancy_edit.php?vacancy_id=<?= $vacancy_id ?>" class="edit-button">Edit Detail Lowongan</a>
                </section>
            </div>
        </body>
</html>