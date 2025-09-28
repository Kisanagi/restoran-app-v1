<?php
include 'db.php';

// --- MODE EDIT ---
$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan=$id_edit");
    $edit_data = mysqli_fetch_assoc($result);
    $edit_mode = true;
}

// --- SIMPAN DATA (Tambah / Edit) ---
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];

    if ($_POST['id_pelanggan'] != '') {
        // UPDATE
        $id = $_POST['id_pelanggan'];
        $query = "UPDATE pelanggan SET nama='$nama', no_hp='$no_hp' WHERE id_pelanggan=$id";
    } else {
        // INSERT
        $query = "INSERT INTO pelanggan (nama, no_hp) VALUES ('$nama', '$no_hp')";
    }
    mysqli_query($conn, $query);
    header("Location: pelanggan.php");
    exit;
}

// --- HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan=$id");
    header("Location: pelanggan.php");
    exit;
}

// --- TAMPILKAN DATA ---
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Data Pelanggan</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="produk.php">Produk</a>
            <a href="pesanan.php">Pesanan</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <h2><?php echo $edit_mode ? "Edit Pelanggan" : "Tambah Pelanggan"; ?></h2>
            <form method="post">
                <input type="hidden" name="id_pelanggan" value="<?php echo $edit_mode ? $edit_data['id_pelanggan'] : ''; ?>">

                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $edit_mode ? $edit_data['nama'] : ''; ?>" required>

                <label>No HP</label>
                <input type="text" name="no_hp" value="<?php echo $edit_mode ? $edit_data['no_hp'] : ''; ?>">

                <button type="submit" name="simpan" class="btn btn-orange">
                    <?php echo $edit_mode ? "Update" : "Simpan"; ?>
                </button>
                <?php if ($edit_mode): ?>
                    <a href="pelanggan.php" class="btn btn-gray">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Pelanggan</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                    <tr>
                        <td><?php echo $row['id_pelanggan']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                        <td>
                            <a href="pelanggan.php?edit=<?php echo $row['id_pelanggan']; ?>" class="btn btn-yellow">Edit</a>
                            <a href="pelanggan.php?hapus=<?php echo $row['id_pelanggan']; ?>" class="btn btn-gray" onclick="return confirm('Hapus data ini?')">Hapus</a>
                        </td>
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