<?php
    include "database.php";

    $sql_select = "SELECT 
                  pelanggan.id_pelanggan,
                  pelanggan.nama_pelanggan, 
                  pelanggan.no_meja, 
                  GROUP_CONCAT(CONCAT(menu.nama_makanan, ' (x', pesanan.jumlah, ')') SEPARATOR '\n') AS daftar_menu,
                  SUM(pesanan.jumlah * menu.harga) AS total_harga
               FROM pesanan
               JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
               JOIN menu ON pesanan.id_menu = menu.id_menu
               GROUP BY pelanggan.id_pelanggan, pelanggan.nama_pelanggan, pelanggan.no_meja";



    $result = $conn->query($sql_select);

    // hapus / selesai melayani pelanggan
    if (isset($_GET['hapus'])) {
        $id_pelanggan = $_GET['hapus'];
    
        // Hapus semua pesanan berdasarkan id_pelanggan
        $sql_hapus_pesanan = "DELETE FROM pesanan WHERE id_pelanggan = $id_pelanggan";
        $sql_hapus_pelanggan = "DELETE FROM pelanggan WHERE id_pelanggan = $id_pelanggan";
        if ($conn->query($sql_hapus_pesanan) && $conn->query($sql_hapus_pelanggan)) {
            header("Location: admin_pesanan.php"); // Redirect ke halaman admin setelah hapus
            exit();
        } else {
            echo "Gagal menghapus semua pesanan: " . $conn->error;
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
</head>
<body>
    <header>
        <h1>Halaman Daftar Pesanan</h1>
        <button onclick="window.location.href='masuk.php'">Keluar</button>
    </header>
    <main>
        <!-- Tabel Pesanan -->
    <h2>Daftar Pesanan</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Menu</th>
            <th>No Meja</th>
            <th>Total</th>
            <th>AKsi</th>
        </tr>
        <?php
        
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nama_pelanggan'] . "</td>";
                echo "<td>" . nl2br($row['daftar_menu']) . "</td>";
                echo "<td>" . $row['no_meja'] . "</td>";
                echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                echo "<td>";
                echo "<a href='admin_pesanan.php?hapus=" . $row['id_pelanggan'] . "' onclick='return confirm(\"Apakah pesanan ini sudah dilayani?\")'>Selesai</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
        }

        ?>
    </table>
    </main>
    <footer>
        <menu>
            <div class="menu"><a href="admin_menu.php">Menu</a></div>
            <div class="menu"><a href="admin_pesanan.php">Pesanan</a></div>
        </menu>
    </footer>
</body>
</html>