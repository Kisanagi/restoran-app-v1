<?php
include 'db.php';

// --- MODE EDIT ---
$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk=$id_edit");
    $edit_data = mysqli_fetch_assoc($result);
    $edit_mode = true;
}

// --- SIMPAN DATA (Tambah / Edit) ---
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id_produk = $_POST['id_produk'];

    if ($id_produk != '') {
        // UPDATE
        $query = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', harga='$harga', stok='$stok' 
                  WHERE id_produk=$id_produk";
    } else {
        // INSERT
        $query = "INSERT INTO produk (nama_produk, kategori, harga, stok) 
                  VALUES ('$nama', '$kategori', '$harga', '$stok')";
    }
    mysqli_query($conn, $query);
    header("Location: produk.php");
    exit;
}

// --- HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk=$id");
    header("Location: produk.php");
    exit;
}

// --- TAMPILKAN DATA ---
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Produk</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Data Produk</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <h2><?php echo $edit_mode ? "Edit Produk" : "Tambah Produk"; ?></h2>
            <form method="post">
                <input type="hidden" name="id_produk" value="<?php echo $edit_mode ? $edit_data['id_produk'] : ''; ?>">

                <label>Nama Produk</label>
                <input type="text" name="nama_produk"
                    value="<?php echo $edit_mode ? $edit_data['nama_produk'] : ''; ?>" required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="Makanan" <?php echo ($edit_mode && $edit_data['kategori'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                    <option value="Appetizer" <?php echo ($edit_mode && $edit_data['kategori'] == 'Appetizer') ? 'selected' : ''; ?>>Appetizer</option>
                    <option value="Minuman" <?php echo ($edit_mode && $edit_data['kategori'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                </select>

                <label>Harga</label>
                <input type="number" name="harga"
                    value="<?php echo $edit_mode ? $edit_data['harga'] : ''; ?>" required>

                <label>Stok</label>
                <input type="number" name="stok"
                    value="<?php echo $edit_mode ? $edit_data['stok'] : ''; ?>" required>

                <button type="submit" name="simpan" class="btn btn-orange">
                    <?php echo $edit_mode ? "Update" : "Simpan"; ?>
                </button>
                <?php if ($edit_mode): ?>
                    <a href="produk.php" class="btn btn-gray">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Produk</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($produk)): ?>
                    <tr>
                        <td><?php echo $row['id_produk']; ?></td>
                        <td><?php echo $row['nama_produk']; ?></td>
                        <td><?php echo $row['kategori']; ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td>
                            <a href="produk.php?edit=<?php echo $row['id_produk']; ?>" class="btn btn-yellow">Edit</a>
                            <a href="produk.php?hapus=<?php echo $row['id_produk']; ?>"
                                class="btn btn-gray" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Restoran App</p>
    </footer>
</body>

</html>