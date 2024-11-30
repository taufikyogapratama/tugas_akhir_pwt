
<?php
// Sertakan file koneksi database
include "database.php";

// **1. PROSES HAPUS DATA**
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql_hapus = "DELETE FROM menu WHERE id = $id";
    if ($conn->query($sql_hapus)) {
        echo "Data berhasil dihapus!";
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }
}

// **2. PROSES EDIT DATA**
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama_makanan = $_POST['nama_makanan'];
    $harga = $_POST['harga'];

    $sql_edit = "UPDATE menu SET nama_makanan = '$nama_makanan', harga = $harga WHERE id = $id";
    if ($conn->query($sql_edit)) {
        echo "Data berhasil diupdate!";
    } else {
        echo "Gagal mengupdate data: " . $conn->error;
    }
}

// **3. SELECT DATA UNTUK DITAMPILKAN**
$sql_select = "SELECT * FROM menu";
$result = $conn->query($sql_select);


// **4 Tambah
if (isset($_POST['tambah'])) {
    // Ambil data dari form
    $nama_makanan = $_POST['nama_makanan'];
    $harga = $_POST['harga'];

    // Debugging: Cek apakah data dari form dikirim
    if (!$nama_makanan || !$harga) {
        die("Form tidak lengkap. Pastikan semua data diisi.");
    }

    // Buat query SQL
    $sql = "INSERT INTO menu (nama_makanan, harga) VALUES ('$nama_makanan', '$harga')";

    // Jalankan query dan cek apakah berhasil
    if ($conn->query($sql)) {
        echo "Berhasil menambahkan data!";
    } else {
        echo "Gagal menambahkan data: " . $conn->error; // Tampilkan error jika query gagal
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu</title>
</head>
<body>
    <h1>Manajemen Menu</h1>

    <!-- Form Tambah Data -->
    <form action="coba.php" method="POST">
        <label for="nama_makanan">Nama Makanan</label>
        <input id="nama_makanan" type="text" name="nama_makanan" required>
        <label for="harga">Harga</label>
        <input id="harga" type="number" name="harga" required>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <hr>

    <!-- Tabel Data -->
    <h2>Daftar Menu</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>No</th>
            <th>Nama Makanan</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nama_makanan'] . "</td>";
                echo "<td>Rp " . $row['harga'] . "</td>";
                echo "<td>";
                echo "<a href='admin.php?hapus=" . $row['id'] . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a> | ";
                echo "<a href='admin.php?edit=" . $row['id'] . "'>Edit</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <hr>

    <!-- Form Edit Data -->
    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql_get_data = "SELECT * FROM menu WHERE id = $id";
        $result_edit = $conn->query($sql_get_data);
        $row_edit = $result_edit->fetch_assoc();
    ?>
        <h2>Edit Data</h2>
        <form action="coba.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
            <label for="nama_makanan">Nama Makanan</label>
            <input id="nama_makanan" type="text" name="nama_makanan" value="<?php echo $row_edit['nama_makanan']; ?>" required>
            <label for="harga">Harga</label>
            <input id="harga" type="number" name="harga" value="<?php echo $row_edit['harga']; ?>" required>
            <button type="submit" name="edit">Simpan</button>
        </form>
    <?php } ?>
</body>
</html>

