<?php
    include "database.php";

    // Untuk insert ke tabel pelanggan
    if (isset($_POST['pesan'])) {
        $nama_pelanggan = $_POST['nama_pelanggan'];
        $no_meja = $_POST['no_meja'];

        if(!$nama_pelanggan || !$no_meja) {
            die("Form tidak lengkap. Pastikan semua data diisi.");
        }

        $sql = "INSERT INTO pelanggan (nama_pelanggan, no_meja) VALUES ('$nama_pelanggan', '$no_meja')";

        if ($conn->query($sql)) {
            header("Location: pelanggan.php");
            exit();
         } else {
             echo "Gagal menambahkan data: " . $conn->error;
         }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pelanggan</title>
</head>
<body>
    <h1>Halaman Pelanggan</h1>
    <form action="pelanggan.php" method="POST">
        <label for="i_nama">Nama</label><br>
        <input id="i_nama" type="text" name="nama_pelanggan" required><br>
        <label for="i_no_meja">Nomor Meja</label><br>
        <input id="i_no_meja" type="number" name="no_meja" required><br>
        <div style="width: 300px; height: 100px; background-color: blue;">
            <h3>Ini nanti menu menu</h3>
        </div>
        <div style="width: 300px; height: 100px; background-color: red;">
            <h3>Ini nanti keranjang</h3>
        </div>
        <button type="submit" name="pesan">Pesan</button>
    </form>
</body>
</html>