<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password</h2>
    <form action="verify_security.php" method="post">
        <label for="contact">Nomor Kontak:</label>
        <input type="text" id="contact" name="contact" required>

        <!-- Atau bisa juga dengan pertanyaan keamanan -->
        <!--
        <label for="security_answer">Jawaban Keamanan:</label>
        <input type="text" id="security_answer" name="security_answer" required>
        -->

        <button type="submit">Verifikasi</button>
    </form>
</body>
</html>
