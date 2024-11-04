<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST["userType"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $contact = $_POST["contact"];

    $additionalData = [];

    if ($userType == "UMKM") {
        $additionalData = [
            'full_name' => $_POST["fullName"],
            'business_name' => $_POST["businessName"],
            'business_type' => $_POST["businessType"],
            'address' => $_POST["address"]
        ];
    } elseif ($userType == "JobSeeker") {
        $additionalData = [
            'full_name' => $_POST["fullName"]
        ];
    }

    if (!preg_match('/^[0-9]+$/', $contact)) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            showNotification('Nomor kontak hanya boleh berisi angka.', 'error');
        });</script>";
    } else {
        $errors = $user->register($userType, $email, $password, $contact, $additionalData);

        if (empty($errors)) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() {
                showNotification('Pendaftaran berhasil!', 'success');
                setTimeout(function() { window.location.href = 'login.php'; }, 2000);
            });</script>";
        } else {
            foreach ($errors as $error) {
                echo "<script>document.addEventListener('DOMContentLoaded', function() {
                    showNotification('$error', 'error');
                });</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SobatKerja - Pendaftaran</title>
        <link rel="stylesheet" href="daftar.css">
        <link rel="stylesheet" href="notification.css">
        <style>
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

            function showFields() {
                const userType = document.getElementById("userType").value;
                const umkmFields = document.getElementById("umkmFields");
        /*      const jobSeekerFields = document.getElementById("jobSeekerFields"); */

                umkmFields.style.display = userType === "UMKM" ? "block" : "none";
        /*     jobSeekerFields.style.display = userType === "JobSeeker" ? "block" : "none"; */
            }

    /*        function updateSkills() {
                const jobField = document.getElementById("jobField").value;
                const skills = document.getElementById("skills");

                skills.innerHTML = ""; 

                const skillOptions = {
                    "Manajemen": ["Manajemen Proyek", "Komunikasi", "Kepemimpinan"],
                    "Keuangan": ["Analisis Data", "Pembukuan", "Pemahaman Pajak"],
                    "Marketing": ["SEO", "Media Sosial", "Email Marketing"],
                    "Penjualan": ["Negosiasi", "Penjualan Produk", "Layanan Pelanggan"],
                    "IT": ["Pemrograman", "Analisis Data", "Keamanan Jaringan"],
                    "Desain": ["Desain Grafis", "UI/UX", "Fotografi"],
                    "Produksi": ["Pemecahan Masalah", "Pengawasan Proses", "Kualitas Produk"],
                    "Operasional": ["Manajemen Inventaris", "Optimasi Proses", "Logistik"],
                    "Logistik": ["Manajemen Rantai Pasokan", "Pengelolaan Gudang", "Distribusi"],
                    "Customer Service": ["Komunikasi", "Layanan Pelanggan", "Pemecahan Masalah"]
                };

                const selectedSkills = skillOptions[jobField] || [];

                selectedSkills.forEach(skill => {
                    const option = document.createElement("option");
                    option.value = skill;
                    option.textContent = skill;
                    skills.appendChild(option);
                });
            } */

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

        <div class="container">
            <div class="logo">
                <img src="assets/icon.svg" alt="Logo" id="icon-logo">
                <img src="assets/logotext.svg" alt="Text Logo" id="text-logo">
            </div>
            <h1>Pendaftaran</h1>

            <form action="register.php" method="post">
                <label for="userType">Jenis Pengguna:</label>
                <select id="userType" name="userType" onchange="showFields()">
                    <option value="">--Pilih Jenis Pengguna--</option>
                    <option value="UMKM">UMKM</option>
                    <option value="JobSeeker">Pencari Kerja</option>
                </select>

                <div id="umkmFields" style="display: none;">
                    <label for="businessName">Nama Usaha:</label>
                    <input type="text" id="businessName" name="businessName" placeholder="Nama usaha Anda">

                    <label for="businessType">Jenis Usaha:</label>
                    <select id="businessType" name="businessType">
                        <option value="">--Pilih Jenis Usaha--</option>
                        <option value="Retail">Retail</option>
                        <option value="Kuliner">Kuliner</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Pertanian">Pertanian</option>
                        <option value="Peternakan">Peternakan</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Kesehatan">Kesehatan</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Keuangan">Keuangan</option>
                    </select>

                    <label for="address">Alamat:</label>
                    <input type="text" id="address" name="address" placeholder="Alamat usaha Anda">
                </div>

                <div id="jobSeekerFields" style="display: none;">
                    
                    <label for="jobField">Bidang Pekerjaan:</label>
                    <select id="jobField" name="jobField" onchange="updateSkills()">
                        <option value="">--Pilih Bidang Pekerjaan--</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Penjualan">Penjualan</option>
                        <option value="IT">IT</option>
                        <option value="Desain">Desain</option>
                        <option value="Produksi">Produksi</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Logistik">Logistik</option>
                        <option value="Customer Service">Customer Service</option>
                    </select>

                    <label for="skills">Skill:</label>
                    <select id="skills" name="skills">
                        <option value="">--Pilih Skill--</option>
                    </select>
                </div>

                <label for="fullName">Nama Lengkap:</label>
                <input type="text" id="fullName" name="fullName" placeholder="Nama lengkap Anda" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="contoh@email.com" required>

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" placeholder="******" required>

                <label for="contact">Nomor Kontak:</label>
                <input type="text" id="contact" name="contact" placeholder="62812xxxxxx" required oninput="validateContactInput(event)">

                <button type="submit">Daftar</button>
            </form>

            <p>Sudah memiliki akun? <a href="login.php">Login di sini</a></p>
        </div>
    </body>
    </html>
