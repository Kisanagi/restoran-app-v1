<?php
include "db.php";

// Hitung total pesanan
$qPesanan = mysqli_query($conn, "SELECT COUNT(*) as total_pesanan FROM pesanan");
$totalPesanan = mysqli_fetch_assoc($qPesanan)['total_pesanan'];

// Hitung total transaksi
$qTransaksi = mysqli_query($conn, "SELECT COUNT(*) as total_transaksi FROM transaksi");
$totalTransaksi = mysqli_fetch_assoc($qTransaksi)['total_transaksi'];

// Hitung total pendapatan
$qPendapatan = mysqli_query($conn, "SELECT SUM(total_bayar) as total_pendapatan FROM transaksi");
$totalPendapatan = mysqli_fetch_assoc($qPendapatan)['total_pendapatan'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Dapur Bunda Bahagia</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Dapur Bunda Bahagia</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="produk.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </nav>
    </header>

    <div class="hero-image">
        <h2 class="hero-text">Selamat Datang di Dapur Bunda Bahagia</h2>
    </div>

    <main>
        <div class="card">
            <h2>📊 Statistik Singkat</h2>
            <p>Total Pesanan: <span class="highlight"><?= $totalPesanan ?></span></p>
            <p>Total Transaksi: <span class="highlight"><?= $totalTransaksi ?></span></p>
            <p>Total Pendapatan: <span class="highlight">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></span></p>
            <a href="laporan.php" class="btn btn-orange">📑 Lihat Laporan Lengkap</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Dapur Bunda Bahagia</p>
    </footer>
</body>

</html>