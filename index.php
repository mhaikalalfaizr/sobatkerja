<?php
session_start();
require_once 'Vacancy.php';

$vacancy = new Vacancy();
$searchResults = null;

$search_keyword = $_GET['search_keyword'] ?? '';
$job_type = $_GET['job_type'] ?? '';
$location = $_GET['location'] ?? '';
$category = $_GET['category'] ?? '';

$searchResults = $vacancy->searchVacancies($search_keyword, $job_type, $location, $category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Lowongan</title>
    <link rel="stylesheet" href="vacancy_search.css">
</head>
<body>
    <header class="main-header">
        <h2>Bekerja dengan UMKM mulai dari sini!</h2>
    </header>
    
<div class="search-container">

    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="search_keyword" placeholder="Masukkan kata kunci..." value="<?php echo htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8'); ?>">
        
        <select name="job_type">
            <option value="">Semua Jenis Pekerjaan</option>
            <option value="Full-time" <?php if ($job_type == 'Full-time') echo 'selected'; ?>>Full-time</option>
            <option value="Part-time" <?php if ($job_type == 'Part-time') echo 'selected'; ?>>Part-time</option>
            <option value="Freelance" <?php if ($job_type == 'Freelance') echo 'selected'; ?>>Freelance</option>
        </select>

        <select name="location">
            <option value="">Semua Lokasi</option>
            <option value="Aceh" <?php if ($location == 'Aceh') echo 'selected'; ?>>Aceh</option>
            <option value="Medan" <?php if ($location == 'Medan') echo 'selected'; ?>>Medan</option>
            <option value="Padang" <?php if ($location == 'Padang') echo 'selected'; ?>>Padang</option>
            <option value="Pekanbaru" <?php if ($location == 'Pekanbaru') echo 'selected'; ?>>Pekanbaru</option>
            <option value="Jambi" <?php if ($location == 'Jambi') echo 'selected'; ?>>Jambi</option>
            <option value="Palembang" <?php if ($location == 'Palembang') echo 'selected'; ?>>Palembang</option>
            <option value="Bengkulu" <?php if ($location == 'Bengkulu') echo 'selected'; ?>>Bengkulu</option>
            <option value="Lampung" <?php if ($location == 'Lampung') echo 'selected'; ?>>Lampung</option>
            <option value="Pangkal Pinang" <?php if ($location == 'Pangkal Pinang') echo 'selected'; ?>>Pangkal Pinang</option>
            <option value="Tanjung Pinang" <?php if ($location == 'Tanjung Pinang') echo 'selected'; ?>>Tanjung Pinang</option>
            <option value="Jakarta" <?php if ($location == 'Jakarta') echo 'selected'; ?>>Jakarta</option>
            <option value="Bandung" <?php if ($location == 'Bandung') echo 'selected'; ?>>Bandung</option>
            <option value="Semarang" <?php if ($location == 'Semarang') echo 'selected'; ?>>Semarang</option>
            <option value="Yogyakarta" <?php if ($location == 'Yogyakarta') echo 'selected'; ?>>Yogyakarta</option>
            <option value="Surabaya" <?php if ($location == 'Surabaya') echo 'selected'; ?>>Surabaya</option>
            <option value="Malang" <?php if ($location == 'Malang') echo 'selected'; ?>>Malang</option>
            <option value="Denpasar" <?php if ($location == 'Denpasar') echo 'selected'; ?>>Denpasar</option>
            <option value="Mataram" <?php if ($location == 'Mataram') echo 'selected'; ?>>Mataram</option>
            <option value="Kupang" <?php if ($location == 'Kupang') echo 'selected'; ?>>Kupang</option>
            <option value="Pontianak" <?php if ($location == 'Pontianak') echo 'selected'; ?>>Pontianak</option>
            <option value="Banjarmasin" <?php if ($location == 'Banjarmasin') echo 'selected'; ?>>Banjarmasin</option>
            <option value="Samarinda" <?php if ($location == 'Samarinda') echo 'selected'; ?>>Samarinda</option>
            <option value="Balikpapan" <?php if ($location == 'Balikpapan') echo 'selected'; ?>>Balikpapan</option>
            <option value="Makassar" <?php if ($location == 'Makassar') echo 'selected'; ?>>Makassar</option>
            <option value="Manado" <?php if ($location == 'Manado') echo 'selected'; ?>>Manado</option>
            <option value="Palu" <?php if ($location == 'Palu') echo 'selected'; ?>>Palu</option>
            <option value="Kendari" <?php if ($location == 'Kendari') echo 'selected'; ?>>Kendari</option>
            <option value="Gorontalo" <?php if ($location == 'Gorontalo') echo 'selected'; ?>>Gorontalo</option>
            <option value="Ambon" <?php if ($location == 'Ambon') echo 'selected'; ?>>Ambon</option>
            <option value="Ternate" <?php if ($location == 'Ternate') echo 'selected'; ?>>Ternate</option>
            <option value="Jayapura" <?php if ($location == 'Jayapura') echo 'selected'; ?>>Jayapura</option>
            <option value="Sorong" <?php if ($location == 'Sorong') echo 'selected'; ?>>Sorong</option>
        </select>

        <select name="category">
            <option value="">Semua Kategori Usaha</option>
            <option value="Retail" <?php if ($category == 'Retail') echo 'selected'; ?>>Retail</option>
            <option value="Kuliner" <?php if ($category == 'Kuliner') echo 'selected'; ?>>Kuliner</option>
            <option value="Jasa" <?php if ($category == 'Jasa') echo 'selected'; ?>>Jasa</option>
            <option value="Teknologi" <?php if ($category == 'Teknologi') echo 'selected'; ?>>Teknologi</option>
            <option value="Kerajinan" <?php if ($category == 'Kerajinan') echo 'selected'; ?>>Kerajinan</option>
            <option value="Pertanian" <?php if ($category == 'Pertanian') echo 'selected'; ?>>Pertanian</option>
            <option value="Peternakan" <?php if ($category == 'Peternakan') echo 'selected'; ?>>Peternakan</option>
            <option value="Fashion" <?php if ($category == 'Fashion') echo 'selected'; ?>>Fashion</option>
            <option value="Kesehatan" <?php if ($category == 'Kesehatan') echo 'selected'; ?>>Kesehatan</option>
            <option value="Pendidikan" <?php if ($category == 'Pendidikan') echo 'selected'; ?>>Pendidikan</option>
            <option value="Keuangan" <?php if ($category == 'Keuangan') echo 'selected'; ?>>Keuangan</option>
        </select>

        <button type="submit">Cari</button>
    </form>

    <section class="results">
        <h3>Hasil Pencarian</h3>
        <?php if ($searchResults && count($searchResults) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul Lowongan</th>
                        <th>Jenis Pekerjaan</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Gaji</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>Rp <?php echo number_format($row['salary'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="<?php echo isset($_SESSION['userType']) ? 'vacancy_detail.php?vacancy_id=' . $row['id'] : 'login.php'; ?>" class="apply-button">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error-message">Lowongan yang dicari tidak ditemukan.</p>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
