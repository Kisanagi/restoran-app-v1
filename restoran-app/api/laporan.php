<?php
include "db.php";

// Total pesanan
$qPesanan = mysqli_query($conn, "SELECT COUNT(*) as total_pesanan FROM pesanan");
$totalPesanan = mysqli_fetch_assoc($qPesanan)['total_pesanan'];

// Total pendapatan
$qPendapatan = mysqli_query($conn, "SELECT SUM(total_bayar) as total_pendapatan FROM transaksi");
$totalPendapatan = mysqli_fetch_assoc($qPendapatan)['total_pendapatan'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Laporan Keuangan</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="produk.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="transaksi.php">Transaksi</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <h2>📊 Ringkasan</h2>
            <p>Total Pesanan: <span class="highlight"><?= $totalPesanan ?></span></p>
            <p>Total Pendapatan: <span class="highlight">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></span></p>
        </div>

        <div class="card">
            <h2>Detail Transaksi</h2>
            <table>
                <tr>
                    <th>ID Transaksi</th>
                    <th>ID Pesanan</th>
                    <th>Metode</th>
                    <th>Total Bayar</th>
                    <th>Tanggal</th>
                </tr>
                <?php
                $transaksi = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY created_at DESC");
                while ($row = mysqli_fetch_assoc($transaksi)): ?>
                    <tr>
                        <td><?= $row['id_transaksi'] ?></td>
                        <td><?= $row['id_pesanan'] ?></td>
                        <td><?= $row['metode_pembayaran'] ?></td>
                        <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Dapur Bunda Bahagia</p>
    </footer>
</body>

</html>