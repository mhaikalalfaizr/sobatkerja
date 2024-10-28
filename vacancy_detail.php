<?php
session_start();

// Cek apakah user sudah login dan memiliki tipe user 'JobSeeker'
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

require_once 'Vacancy.php';
require_once 'User.php';

// Ambil ID lowongan dari parameter URL
$vacancy_id = $_GET['vacancy_id'] ?? null;

// Ambil detail lowongan dari database
$query = "SELECT * FROM Vacancies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vacancy_id);
$stmt->execute();
$result = $stmt->get_result();
$vacancy = $result->fetch_assoc();

if (!$vacancy) {
    echo "Lowongan tidak ditemukan.";
    exit();
}

// Proses pengiriman lamaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobseeker_id = $_SESSION['user_id'];
    $upload_dir = 'jobseekercv/';
    $cv_file = $upload_dir . basename($_FILES['cv']['name']);
    $file_type = strtolower(pathinfo($cv_file, PATHINFO_EXTENSION));

    // Validasi file
    if ($file_type != "pdf") {
        echo "<p class='error-message'>CV harus dalam format PDF.</p>";
    } elseif (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_file)) {
        // Simpan lamaran di database
        $query = "INSERT INTO Applications (jobseeker_id, vacancy_id, application_date, cv_path) VALUES (?, ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $jobseeker_id, $vacancy_id, $cv_file);

        if ($stmt->execute()) {
            echo "<p class='success-message'>Lamaran berhasil dikirim!</p>";
        } else {
            echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error-message'>Gagal mengunggah CV. Silakan coba lagi.</p>";
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
</head>
<body>
    <div class="detail-container">
        <h2>Detail Lowongan</h2>
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
