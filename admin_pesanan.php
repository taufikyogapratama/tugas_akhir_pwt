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

    if (isset($_GET['hapus'])) {
        $id_pelanggan = $_GET['hapus'];
    
        $sql_hapus_pesanan = "DELETE FROM pesanan WHERE id_pelanggan = $id_pelanggan";
        $sql_hapus_pelanggan = "DELETE FROM pelanggan WHERE id_pelanggan = $id_pelanggan";
        if ($conn->query($sql_hapus_pesanan) && $conn->query($sql_hapus_pelanggan)) {
            header("Location: admin_pesanan.php");
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
            color: black;
            display: flex;
            justify-content: center;
            align-items: center;
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
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .data table thead {
            background-color: #4CAF50;
            color: white;
        }

        .data table thead th {
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
        }

        .data table tbody tr {
            background-color: #f9f9f9;
        }

        .data table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .data table tbody tr:hover {
            background-color: #e9e9e9;
        }

        .data table tbody td {
            padding: 12px 15px;
            text-align: left;
            font-size: 1rem;
            color: #333;
        }

        .data table tbody td a {
            text-decoration: none;
            color: white;
            background-color: #f44336;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .data table tbody td a:hover {
            background-color: #d32f2f;
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
            text-decoration: none;
            background-color: white;
            border: 1px solid black;
            color: black;
        }
        
        footer .menu2{
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
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
                font-size: 1rem;
                height: 60px;
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
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }

            .data table thead th {
                padding: 3px;
                text-align: left;
                font-size: 0.7rem;
            }

            .data table tr th{
                font-size: 0.7rem;
            }

            .data table tbody td {
                padding: 8px 10px;
                text-align: left;
                font-size: 0.7rem;
                color: #333;
            }

            .data table tbody td a {
                text-decoration: none;
                color: white;
                background-color: #f44336;
                padding: 3px 7px;
                border-radius: 5px;
                font-size: 0.8rem;
                transition: background-color 0.3s ease;
                font-size: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <p>Halaman Daftar Pesanan</p>
        <button onclick="window.location.href='/tugas_akhir_pwt'">Keluar</button>
    </header>
    <main>
        <!-- Tabel Pesanan -->
    <h2 class="judul">Daftar Pesanan</h2>
    <section class="data">
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
    </section>
    </main>
    <footer>
        <a class="menu1" href="admin_menu.php">Menu</a>
        <a class="menu2" href="admin_pesanan.php">Pesanan</a>
    </footer>
    <div class="kosong" style="height: 70px;">

    </div>
</body>
</html>