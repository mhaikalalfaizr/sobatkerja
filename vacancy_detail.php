<?php
session_start();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

require_once 'Vacancy.php';

$vacancy_id = $_GET['vacancy_id'] ?? null;

$vacancyObj = new Vacancy();
$vacancy = $vacancyObj->getVacancyDetails($vacancy_id);

if (!$vacancy) {
    echo "Lowongan tidak ditemukan.";
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobseeker_id = $_SESSION['user_id'];
    $upload_dir = 'jobseekercv/';
    $cv_file = $upload_dir . basename($_FILES['cv']['name']);
    $file_type = strtolower(pathinfo($cv_file, PATHINFO_EXTENSION));

    if ($file_type != "pdf") {
        $errorMessage = 'CV harus dalam format PDF.';
    } elseif (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_file)) {
        $applicationResult = $vacancyObj->createApplication($jobseeker_id, $vacancy_id, $cv_file);

        if ($applicationResult) {
            $successMessage = 'Lamaran berhasil dikirim! Mengalihkan ke dashboard...';
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('$successMessage', 'success');
                    setTimeout(function() { window.location.href = 'dashboard_jobseeker.php'; }, 2000);
                });
            </script>";
        } else {
            $errorMessage = 'Gagal mengirim lamaran. Silakan coba lagi.';
        }
    } else {
        $errorMessage = 'Gagal mengunggah CV. Silakan coba lagi.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan</title>
    <link rel="stylesheet" href="vacancy_detail.css">
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
    <div class="detail-container">
        <h2>Detail Lowongan</h2>
        <p><strong>Nama Usaha:</strong> <?php echo htmlspecialchars($vacancy['business_name']); ?></p>
        <p><strong>Judul Lowongan:</strong> <?php echo htmlspecialchars($vacancy['title']); ?></p>
        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($vacancy['description']); ?></p>
        <p><strong>Persyaratan:</strong> <?php echo htmlspecialchars($vacancy['requirements']); ?></p>
        <p><strong>Jenis Pekerjaan:</strong> <?php echo htmlspecialchars($vacancy['job_type']); ?></p>
        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($vacancy['location']); ?></p>
        <p><strong>Kategori Usaha:</strong> <?php echo htmlspecialchars($vacancy['category']); ?></p>

        <h3>Lamar Pekerjaan Ini</h3>
        <form action="vacancy_detail.php?vacancy_id=<?php echo $vacancy_id; ?>" method="POST" enctype="multipart/form-data" class="apply-form">
            <label for="cv">Unggah CV (PDF):</label>
            <input type="file" name="cv" id="cv" accept="application/pdf" required>
            <button type="submit" class="apply-button">Kirim Lamaran</button>
        </form>
    </div>
</body>
</html>
