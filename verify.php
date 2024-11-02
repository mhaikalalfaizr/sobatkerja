<?php
require_once 'Database.php';

if (isset($_GET['token']) && isset($_GET['type'])) {
    $token = $_GET['token'];
    $userType = $_GET['type'];
    
    $db = Database::getInstance()->getConnection();

    if ($userType == "JobSeeker") {
        $query = "UPDATE jobseeker SET is_verified = 1 WHERE verification_token = ? AND is_verified = 0";
    } elseif ($userType == "UMKM") {
        $query = "UPDATE umkm SET is_verified = 1 WHERE verification_token = ? AND is_verified = 0";
    } else {
        echo "Tipe pengguna tidak valid.";
        exit();
    }

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Email Anda telah berhasil diverifikasi. Anda dapat login sekarang.";
    } else {
        echo "Token verifikasi tidak valid atau email sudah diverifikasi.";
    }

    $stmt->close();
} else {
    echo "Token verifikasi tidak ditemukan.";
}
?>
