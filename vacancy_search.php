<?php
session_start();
require_once 'Vacancy.php';

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'JobSeeker') {
    header('Location: login.php');
    exit();
}

$vacancy = new Vacancy();
$searchResults = null;

$search_keyword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_keyword = isset($_POST['search_keyword']) ? $_POST['search_keyword'] : '';
    echo htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8');
    $job_type = $_POST['job_type'] ?? '';
    $location = $_POST['location'] ?? '';
    $category = $_POST['category'] ?? '';
    $searchResults = $vacancy->searchVacancies($search_keyword, $job_type, $location, $category);
}
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
<div class="search-container">
    <header>
        <h2>Cari Lowongan</h2>
    </header>

    <form action="vacancy_search.php" method="POST" class="search-form">
        <input type="text" name="search_keyword" placeholder="Masukkan kata kunci..." value="<?php echo htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8'); ?>">
        
        <select name="job_type">
            <option value="">Semua Jenis Pekerjaan</option>
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Freelance">Freelance</option>
        </select>

        <select name="location">
            <option value="">Semua Lokasi</option>
            <option value="Aceh">Aceh</option>
            <option value="Medan">Medan</option>
            <option value="Padang">Padang</option>
            <option value="Pekanbaru">Pekanbaru</option>
            <option value="Jambi">Jambi</option>
            <option value="Palembang">Palembang</option>
            <option value="Bengkulu">Bengkulu</option>
            <option value="Lampung">Lampung</option>
            <option value="Pangkal Pinang">Pangkal Pinang</option>
            <option value="Tanjung Pinang">Tanjung Pinang</option>
            <option value="Jakarta">Jakarta</option>
            <option value="Bandung">Bandung</option>
            <option value="Semarang">Semarang</option>
            <option value="Yogyakarta">Yogyakarta</option>
            <option value="Surabaya">Surabaya</option>
            <option value="Malang">Malang</option>
            <option value="Denpasar">Denpasar</option>
            <option value="Mataram">Mataram</option>
            <option value="Kupang">Kupang</option>
            <option value="Pontianak">Pontianak</option>
            <option value="Banjarmasin">Banjarmasin</option>
            <option value="Samarinda">Samarinda</option>
            <option value="Balikpapan">Balikpapan</option>
            <option value="Makassar">Makassar</option>
            <option value="Manado">Manado</option>
            <option value="Palu">Palu</option>
            <option value="Kendari">Kendari</option>
            <option value="Gorontalo">Gorontalo</option>
            <option value="Ambon">Ambon</option>
            <option value="Ternate">Ternate</option>
            <option value="Jayapura">Jayapura</option>
            <option value="Sorong">Sorong</option>
        </select>

        <select name="category">
            <option value="">Semua Kategori Usaha</option>
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
                        <th>Lamar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><a href="vacancy_detail.php?vacancy_id=<?php echo $row['id']; ?>" class="apply-button">Detail</a></td>
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
