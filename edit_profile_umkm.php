<?php
session_start();

require_once 'database.php';
$conn = Database::getInstance()->getConnection();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

$umkm_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_name = $_POST['business_name'];
    $business_type = $_POST['business_type'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $updateQuery = "
        UPDATE UMKM SET 
        business_name = ?, business_type = ?, address = ?, contact = ? 
        WHERE umkm_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $business_name, $business_type, $address, $contact, $umkm_id);

    if ($stmt->execute()) {
        header("Location: dashboard_umkm.php?update=success");
        exit();
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
}

$profileQuery = "SELECT business_name, business_type, address, contact FROM UMKM WHERE umkm_id = ?";
$profileStmt = $conn->prepare($profileQuery);
$profileStmt->bind_param("i", $umkm_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$umkmProfile = $profileResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil UMKM</title>
    <link rel="stylesheet" href="dashboard_umkm.css">
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profil UMKM</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="edit_profile_umkm.php" method="post">
            <label for="business_name">Nama Usaha:</label>
            <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($umkmProfile['business_name']); ?>" required>

            <label for="business_type">Jenis Usaha:</label>
            <input type="text" id="business_type" name="business_type" value="<?php echo htmlspecialchars($umkmProfile['business_type']); ?>" required>

            <label for="address">Alamat:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($umkmProfile['address']); ?>" required>

            <label for="contact">Kontak:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($umkmProfile['contact']); ?>" required>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
