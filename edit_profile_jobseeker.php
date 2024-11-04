<?php
session_start();
require_once 'database.php';
$conn = Database::getInstance()->getConnection();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

$jobseeker_id = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    if (!preg_match('/^[0-9]+$/', $contact)) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            showNotification('Nomor kontak hanya boleh berisi angka.', 'error');
        });</script>";
    } else {
        $updateQuery = "UPDATE JobSeeker SET full_name = ?, email = ?, contact = ? WHERE jobseeker_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $full_name, $email, $contact, $jobseeker_id);

        if ($stmt->execute()) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() {
                showNotification('Perubahan berhasil disimpan!', 'success');
                setTimeout(function() { window.location.href = 'dashboard_jobseeker.php'; }, 2000);
            });</script>";
        } else {
            echo "<script>document.addEventListener('DOMContentLoaded', function() {
                showNotification('Gagal memperbarui profil.', 'error');
            });</script>";
        }
    }
}

$profileQuery = "SELECT full_name, email, contact FROM JobSeeker WHERE jobseeker_id = ?";
$profileStmt = $conn->prepare($profileQuery);
$profileStmt->bind_param("i", $jobseeker_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$jobseekerProfile = $profileResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Job Seeker</title>
    <link rel="stylesheet" href="edit_profile.css">
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

        function validateContactInput(event) {
            const contactInput = event.target;
            const contactValue = contactInput.value;

            if (!/^\d*$/.test(contactValue)) {
                contactInput.value = contactValue.replace(/\D/g, '');
                showNotification('Nomor kontak hanya boleh berisi angka.', 'error');
            }
        }
    </script>
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profil Job Seeker</h2>
        <form action="edit_profile_jobseeker.php" method="post">
            <label for="full_name">Nama Lengkap:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($jobseekerProfile['full_name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($jobseekerProfile['email']); ?>" required>

            <label for="contact">Kontak:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($jobseekerProfile['contact']); ?>" required oninput="validateContactInput(event)">

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>