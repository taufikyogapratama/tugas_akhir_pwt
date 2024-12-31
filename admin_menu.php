<?php

session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: validasi_admin.php"); // Jika belum login, arahkan ke halaman validasi_admin.php
    exit();
}
// Sertakan file koneksi database
include "database.php";

// **1. PROSES HAPUS DATA**
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql_hapus = "DELETE FROM menu WHERE id_menu = $id";
    if ($conn->query($sql_hapus)) {
       header("Location: admin_menu.php"); // Redirect ke admin.php
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
        header("Location: admin_menu.php");
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
        <section id="edit-menu">
            <h2>Edit Menu</h2>
            <form action="admin_menu.php" method="POST">
                <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>"> <!-- ID menu disembunyikan -->
                <label for="nama_makanan">Nama Makanan</label><br>
                <input id="nama_makanan" type="text" name="nama_makanan" value="<?php echo $row['nama_makanan']; ?>"><br>
                <label for="harga">Harga</label><br>
                <input id="harga" type="number" name="harga" value="<?php echo $row['harga']; ?>"><br>
                <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                    <button onclick="window.location.href='admin_menu.php'">Batal</button>
                    <button type="submit" name="edit">Simpan</button>
                </div>
            </form>
        </section>
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
       header("Location: admin_menu.php"); // Redirect ke admin.php
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
    <title>Halaman Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", serif;
        }
        
        header{
            width: 100%;
            height: 100px;
            background-color: #4CAF50;
            display: flex;
            justify-content: space-between;;
            align-items: center;
            padding: 0 5.78vw;
        }

        header p{
            font-size: 2.25rem;
            color: white;
            font-weight: bold;
        }

        header button{
            padding: 7px 20px;
            background-color: red;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
        }

        .judul{
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: space-between;;
            align-items: center;
            padding: 0 5.78vw;
        }

        .judul button{
            padding: 7px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
        }

        .data {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 200px;
            padding: 20px;
        }

        .data table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data th, .data td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .data tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        footer{
            width: 100%;
            display: flex;
            height: 70px;
            position: fixed;
            bottom: 0;
        }

        footer a{
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        footer .menu1{
            background-color: #4CAF50;
            text-decoration: none;
            color: white;
        }

        footer .menu2{
            background-color: white;
            text-decoration: none;
            color: black;
            border: 1px solid black
        }

        .kosong{
            height: 70px;
        }

        #edit-menu{
            position: absolute;
            top: 250px;
            background-color: white;
            left: 42%;
            padding: 30px;
            border-radius: 20px;
            border: 1px solid black;
        }

        #tambah-menu{
            display: none;
            position: absolute;
            top: 250px;
            background-color: white;
            left: 42%;
            padding: 30px;
            border-radius: 20px;
            border: 1px solid black;
        }

        @media only screen and (max-width: 768px) {
            header p{
                font-size: 1.25rem;
            }

            header button{
                font-size: 0.8rem;
                padding: 7px 10px;
            }

            .judul{
                width: 100%;
                height: 80px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 5.78vw;
            }

            .judul h2{
                font-size: 1rem;
            }

            .judul button{
                padding: 5px 10px;
                background-color: #4CAF50;
                border: none;
                color: white;
                font-weight: bold;
                border-radius: 5px;
                cursor: pointer;
                font-size: 0.7rem;
            }

            footer{
                width: 100%;
                display: flex;
                height: 50px;
                position: fixed;
                bottom: 0;
            }

            .kosong{
                width: 50px;
            }

            .data {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                min-height: 150px;
                padding: 8px;
            }

            .data table {
                width: 95%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .data th, .data td {
                border: 1px solid #ddd;
                padding: 5px;
                text-align: left;
                font-size: 0.8rem;
            }

            #edit-menu{
                position: absolute;
                top: 250px;
                background-color: white;
                left: 11%;
                padding: 30px;
                border-radius: 20px;
                border: 1px solid black;
            }

            #tambah-menu{
                display: none;
                position: absolute;
                top: 250px;
                background-color: white;
                left: 11%;
                padding: 30px;
                border-radius: 20px;
                border: 1px solid black;
            }
        }
    </style>
</head>
<body>
    <header>
        <p>Manajemen Menu</p>
        <button onclick="window.location.href='logout.php'">Keluar</button>
    </header>

    <!-- Form Tambah Data -->
    <!-- style="display: none; position: absolute; top: 250px; background-color: white; left: 42%; padding: 30px; border-radius: 20px; border: 1px solid black;" -->
     <section id="tambah-menu">
        <h1 style="margin-bottom: 20px;">Tambah Menu</h1>
        <form action="admin_menu.php" method="POST">
            <label for="nama_makanan">Nama Makanan</label><br>
            <input id="nama_makanan" type="text" name="nama_makanan" required><br>
            <label for="harga">Harga</label><br>
            <input id="harga" type="number" name="harga" required><br>
            <div class="btn" style="display: flex; justify-content: space-between; margin-top: 15px;">
                <button onclick="sembunyikanDivTambah()">Batal</button>
                <button type="submit" name="tambah">Tambah</button>
            </div>
        </form>
    </section>

    <!-- Tabel Data -->
     <section class="judul">
        <h2>Daftar Menu</h2>
        <button onclick="tampilkanDivTambah()">Tambah Menu</button>
    </section>

    <section class="data">
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
                    echo "<a href='admin_menu.php?edit=" . $row['id_menu'] . "'>Edit</a> | ";
                    echo "<a href='admin_menu.php?hapus=" . $row['id_menu'] . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
            }
            ?>
        </table>
    </section>
    <footer>
        <a class="menu1" href="admin_menu.php">Menu</a>
        <a class="menu2" href="admin_pesanan.php">Pesanan</a>
    </footer>
    <div class="kosong">

    </div>
    <script>
        function tampilkanDivTambah() {
          const div_tambah = document.getElementById("tambah-menu");
          div_tambah.style.display = "block";
        }
        function sembunyikanDivTambah() {
          const div_tambah = document.getElementById("tambah-menu");
          div_tambah.style.display = "none";
        }
    </script>
    </script>
</body>
</html>