<?php
include "db.php";

// Tambah pesanan (multi produk)
if (isset($_POST['simpan'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $produk = $_POST['produk'];
    $jumlah = $_POST['jumlah'];

    // Simpan pesanan utama
    mysqli_query($conn, "INSERT INTO pesanan (id_pelanggan,total_harga,tanggal) VALUES ('$id_pelanggan',0,NOW())");
    $id_pesanan = mysqli_insert_id($conn);

    $total = 0;
    for ($i = 0; $i < count($produk); $i++) {
        $id_produk = $produk[$i];
        $jml = $jumlah[$i];

        if ($jml <= 0) continue;

        $qProduk = mysqli_query($conn, "SELECT harga, stok FROM produk WHERE id_produk=$id_produk");
        $p = mysqli_fetch_assoc($qProduk);

        if ($p['stok'] < $jml) {
            echo "<script>alert('Stok produk tidak mencukupi!');</script>";
            continue;
        }

        $subtotal = $p['harga'] * $jml;
        $total += $subtotal;

        mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan,id_produk,jumlah,subtotal) 
                             VALUES ('$id_pesanan','$id_produk','$jml','$subtotal')");

        mysqli_query($conn, "UPDATE produk SET stok = stok - $jml WHERE id_produk=$id_produk");
    }

    mysqli_query($conn, "UPDATE pesanan SET total_harga=$total WHERE id_pesanan=$id_pesanan");

    header("Location: pesanan.php");
    exit;
}

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama");
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk");

$pesanan = mysqli_query($conn, "
    SELECT p.id_pesanan, pl.nama, p.total_harga, p.tanggal
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan=pl.id_pelanggan
    ORDER BY p.id_pesanan DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesanan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- HEADER -->
    <header>
        <h1>Data Pesanan</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="produk.php">Produk</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <h2>Tambah Pesanan</h2>
        <form method="post">
            <label>Pelanggan</label>
            <select name="id_pelanggan" required>
                <option value="">--Pilih Pelanggan--</option>
                <?php while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                    <option value="<?= $row['id_pelanggan'] ?>"><?= $row['nama'] ?></option>
                <?php endwhile; ?>
            </select>

            <div id="produk-list">
                <div class="produk-item">
                    <select name="produk[]" required>
                        <?php
                        mysqli_data_seek($produk, 0);
                        while ($row = mysqli_fetch_assoc($produk)): ?>
                            <option value="<?= $row['id_produk'] ?>"><?= $row['nama_produk'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="jumlah[]" placeholder="Jumlah" required>
                </div>
            </div>

            <button type="button" class="btn btn-gray" onclick="tambahProduk()">+ Tambah Produk</button>
            <button type="submit" name="simpan" class="btn btn-orange">Simpan</button>
        </form>

        <h2>Daftar Pesanan</h2>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Detail Produk</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($pesanan)): ?>
                <tr>
                    <td><?= $row['id_pesanan'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td>
                        <ul>
                            <?php
                            $idp = $row['id_pesanan'];
                            $detail = mysqli_query($conn, "
                                SELECT pr.nama_produk, d.jumlah, d.subtotal
                                FROM detail_pesanan d
                                JOIN produk pr ON d.id_produk=pr.id_produk
                                WHERE d.id_pesanan=$idp
                            ");
                            while ($d = mysqli_fetch_assoc($detail)):
                            ?>
                                <li><?= $d['nama_produk'] ?> (<?= $d['jumlah'] ?>) - Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= $row['tanggal'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Dapur Bunda Bahagia</p>
    </footer>
    <script>
        function tambahProduk() {
            const div = document.createElement('div');
            div.className = 'produk-item';
            div.innerHTML = document.querySelector('.produk-item').innerHTML;
            div.querySelector('input').value = "";
            document.getElementById('produk-list').appendChild(div);
        }
    </script>
</body>

</html>