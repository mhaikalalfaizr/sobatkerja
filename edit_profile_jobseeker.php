<?php
session_start();

require_once 'database.php';
$conn = Database::getInstance()->getConnection();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

$jobseeker_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];

    $updateQuery = "
        UPDATE JobSeeker SET 
        full_name = ?, contact = ? 
        WHERE jobseeker_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $full_name, $contact, $jobseeker_id);

    if ($stmt->execute()) {
        header("Location: dashboard_jobseeker.php?update=success");
        exit();
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
}

$profileQuery = "SELECT full_name, contact FROM JobSeeker WHERE jobseeker_id = ?";
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
    <link rel="stylesheet" href="dashboard_jobseeker.css">
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profil Job Seeker</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="edit_profile_jobseeker.php" method="post">
            <label for="full_name">Nama Lengkap:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($jobseekerProfile['full_name']); ?>" required>

            <label for="contact">Kontak:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($jobseekerProfile['contact']); ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
