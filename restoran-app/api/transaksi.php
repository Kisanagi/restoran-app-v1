<?php
include "db.php";

// Proses pembayaran langsung (tanpa input jumlah)
if (isset($_GET['bayar'])) {
    $id_pesanan = $_GET['bayar'];

    // Ambil total harga dari pesanan
    $q = mysqli_query($conn, "SELECT total_harga FROM pesanan WHERE id_pesanan=$id_pesanan");
    $pesanan = mysqli_fetch_assoc($q);
    $total = $pesanan['total_harga'];

    // Simpan ke transaksi
    mysqli_query($conn, "INSERT INTO transaksi (id_pesanan, total_bayar, tanggal) 
                         VALUES ('$id_pesanan', '$total', NOW())");

    // Redirect setelah bayar
    header("Location: transaksi.php");
    exit;
}

// Ambil data transaksi
$transaksi = mysqli_query($conn, "
    SELECT t.id_transaksi, p.id_pesanan, pl.nama, t.total_bayar, t.tanggal
    FROM transaksi t
    JOIN pesanan p ON t.id_pesanan=p.id_pesanan
    JOIN pelanggan pl ON p.id_pelanggan=pl.id_pelanggan
    ORDER BY t.id_transaksi DESC
");

// Ambil data pesanan yang belum dibayar
$belum = mysqli_query($conn, "
    SELECT p.id_pesanan, pl.nama, p.total_harga, p.tanggal
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan=pl.id_pelanggan
    WHERE p.id_pesanan NOT IN (SELECT id_pesanan FROM transaksi)
    ORDER BY p.id_pesanan DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Data Transaksi</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="produk.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="laporan.php">Laporan</a>
        </nav>
    </header>

    <main>
        <h2>Pesanan Belum Dibayar</h2>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($belum)): ?>
                <tr>
                    <td><?= $row['id_pesanan'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>
                        <a href="transaksi.php?bayar=<?= $row['id_pesanan'] ?>" class="btn">Proses Pembayaran</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Daftar Transaksi</h2>
        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Total Bayar</th>
                <th>Tanggal</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($transaksi)): ?>
                <tr>
                    <td><?= $row['id_transaksi'] ?></td>
                    <td><?= $row['id_pesanan'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <td><?= $row['tanggal'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Dapur Bunda Bahagia</p>
    </footer>
</body>

</html>