<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Perubahan: Memproses POST
    if (isset($_POST['nama']) && isset($_POST['password'])) {
        $nama = $_POST['nama']; // Perubahan: Menggunakan $_POST
        $password = $_POST['password']; // Perubahan: Menggunakan $_POST

        $nama = htmlspecialchars($nama);
        $password = htmlspecialchars($password);

        if ($nama === "adminhebat" && $password === "admin1976") {
            header("Location: admin_menu.php");
            exit(); // Penting untuk menghentikan eksekusi setelah redirect
        } else {
            // Menggunakan session untuk mengirim pesan ke halaman yang sama
            session_start();
            $_SESSION['error_message'] = "Maaf, Anda tidak memiliki akses untuk mengakses halaman admin";
            header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"])); // Redirect kembali ke form
            exit();
        }
    } else {
        echo "Form belum disubmit."; // Ini mungkin tidak terlihat karena redirect
    }
}

// Memeriksa pesan error dari session
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']); // Menghapus pesan dari session setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header{
            width: 100%;
            height: 100px;
            background-color: #4CAF50;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header h1{
            color: white;
        }

        main{
            padding: 20px;
            background-color: #4CAF50;
            width: 360px;
            height: 310px;
            margin: 90px auto 0 auto;
            border-radius: 20px
        }

        main p{
            font-size: 0.5 rem;
            text-align: center;
            margin-bottom: 18px;
            color: white;
        }

        main form .kolom-input{
            height: 28px;
            border-radius: 10px;
            border: none;
            width: 100%;
            padding-left: 10px;
        }

        main form .btn{
            display: flex;
            margin-top: 15px;
            justify-content: space-between;
            padding: 0 2px;
        }

        main form .btn .btn-bawah{
            padding: 5px;
            width: 80px;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Validasi Admin</h1>
    </header>
    <main>
        <p>Tolong masukkan nama dan password untuk memastika bahwa Anda memang admin</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="nama">Nama</label><br>
            <input class="kolom-input" type="text" id="nama" name="nama" required><br>
            <label for="password">Password</label><br>
            <input class="kolom-input" type="password" id="password" name="password" required><br>
            <div class="btn">
                <button class="btn-bawah" onclick="window.location.href='/tugas_akhir_pwt'">Kembali</button>
                <input class="btn-bawah" type="submit" value="Masuk">
            </div>
        </form>
    </main>
</body>
</html>