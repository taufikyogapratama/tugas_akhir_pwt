<?php
// Sertakan file koneksi database
include "database.php";

// **1. PROSES HAPUS DATA**
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql_hapus = "DELETE FROM menu WHERE id_menu = $id";
    if ($conn->query($sql_hapus)) {
       header("Location: admin.php"); // Redirect ke admin.php
       exit();
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }
}

// Jika tombol "Simpan" ditekan
if (isset($_POST['edit'])) {
    // Ambil data dari form
    $id = $_POST['id_menu']; // Ambil ID menu dari input hidden
    $nama_makanan = $_POST['nama_makanan']; // Ambil nama makanan dari form
    $harga = $_POST['harga']; // Ambil harga dari form

    // Query untuk memperbarui data di database
    $sql_edit = "UPDATE menu SET nama_makanan = '$nama_makanan', harga = $harga WHERE id_menu = $id";

    // Cek apakah query berhasil
    if ($conn->query($sql_edit)) {
        // Redirect ke halaman admin setelah berhasil update
        header("Location: admin.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $conn->error;
    }
}

// Jika parameter "edit" ada di URL
if (isset($_GET['edit'])) {
    $id = $_GET['edit']; // Ambil ID menu dari URL
    $sql = "SELECT * FROM menu WHERE id_menu = $id"; // Query untuk mendapatkan data menu berdasarkan id_menu
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Ambil data hasil query
        ?>
        <!-- Tampilkan form edit -->
        <h2>Edit Menu</h2>
        <form action="admin.php" method="POST">
            <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>"> <!-- ID menu disembunyikan -->
            <label for="nama_makanan">Nama Makanan</label>
            <input id="nama_makanan" type="text" name="nama_makanan" value="<?php echo $row['nama_makanan']; ?>">
            <label for="harga">Harga</label>
            <input id="harga" type="number" name="harga" value="<?php echo $row['harga']; ?>">
            <button type="submit" name="edit">Simpan</button>
        </form>
        <?php
    } else {
        echo "Data tidak ditemukan!";
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
       header("Location: admin.php"); // Redirect ke admin.php
       exit(); // Hentikan eksekusi script
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
    <h1>Tambah</h1>
    <form action="admin.php" method="POST">
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
                echo "<a href='admin.php?hapus=" . $row['id_menu'] . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a> | ";
                echo "<a href='admin.php?edit=" . $row['id_menu'] . "'>Edit</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
</body>
</html>