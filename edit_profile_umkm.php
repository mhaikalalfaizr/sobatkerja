<?php
session_start();
require_once 'database.php';
require_once 'User.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'UMKM') {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$_SESSION['reset_email'] = $_SESSION['user_email'];

$umkm_id = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';

$conn = Database::getInstance()->getConnection();
$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $business_name = $_POST['business_name'];
    $business_type = $_POST['business_type'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    if (!preg_match('/^[0-9]+$/', $contact)) {
        $errorMessage = "Nomor kontak hanya boleh berisi angka.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Format email tidak valid.";
    } else {
        $updateQuery = "
            UPDATE UMKM SET 
            full_name = ?, business_name = ?, business_type = ?, address = ?, contact = ?, email = ?
            WHERE umkm_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssssi", $full_name, $business_name, $business_type, $address, $contact, $email, $umkm_id);

        if ($stmt->execute()) {
            $successMessage = "Perubahan berhasil disimpan!";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('$successMessage', 'success');
                });
            </script>";
        } else {
            $errorMessage = "Gagal memperbarui profil: " . $stmt->error;
        }
    }
}

$profile = $user->getProfile($umkm_id, 'UMKM');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil UMKM</title>
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

        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($successMessage): ?>
                showNotification("<?= $successMessage ?>", "success");
            <?php elseif ($errorMessage): ?>
                showNotification("<?= $errorMessage ?>", "error");
            <?php endif; ?>
        });

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
    <div class="logo">
        <img src="assets/icon.svg" alt="Logo" id="icon-logo">
        <img src="assets/logotext.svg" alt="Text Logo" id="text-logo">
    </div>
    <h2>Edit Profil UMKM</h2>
    <form action="edit_profile_umkm.php" method="post">
        <label for="full_name">Nama Lengkap:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>

        <label for="business_name">Nama Usaha:</label>
        <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($profile['business_name']); ?>" required>

        <label for="business_type">Jenis Usaha:</label>
        <select id="business_type" name="business_type" required>
            <option value="">--Pilih Jenis Usaha--</option>
            <option value="Retail" <?php if ($profile['business_type'] == 'Retail') echo 'selected'; ?>>Retail</option>
            <option value="Kuliner" <?php if ($profile['business_type'] == 'Kuliner') echo 'selected'; ?>>Kuliner</option>
            <option value="Jasa" <?php if ($profile['business_type'] == 'Jasa') echo 'selected'; ?>>Jasa</option>
            <option value="Teknologi" <?php if ($profile['business_type'] == 'Teknologi') echo 'selected'; ?>>Teknologi</option>
            <option value="Kerajinan" <?php if ($profile['business_type'] == 'Kerajinan') echo 'selected'; ?>>Kerajinan</option>
            <option value="Pertanian" <?php if ($profile['business_type'] == 'Pertanian') echo 'selected'; ?>>Pertanian</option>
            <option value="Peternakan" <?php if ($profile['business_type'] == 'Peternakan') echo 'selected'; ?>>Peternakan</option>
            <option value="Fashion" <?php if ($profile['business_type'] == 'Fashion') echo 'selected'; ?>>Fashion</option>
            <option value="Kesehatan" <?php if ($profile['business_type'] == 'Kesehatan') echo 'selected'; ?>>Kesehatan</option>
            <option value="Pendidikan" <?php if ($profile['business_type'] == 'Pendidikan') echo 'selected'; ?>>Pendidikan</option>
            <option value="Keuangan" <?php if ($profile['business_type'] == 'Keuangan') echo 'selected'; ?>>Keuangan</option>
        </select>

        <label for="address">Alamat:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($profile['address']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required>

        <label for="contact">Kontak:</label>
        <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($profile['contact']); ?>" required oninput="validateContactInput(event)">

        <button type="submit">Simpan Perubahan</button>
    </form>

    <p class="change-password-link">
        <br>
        Ingin mengubah password? <a href="password_reset.php">Klik di sini</a>
    </p>
</div>
</body>
</html>