<?php
session_start();

// Periksa apakah admin sudah login
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_menu.php"); // Jika sudah login, arahkan ke halaman admin
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama']) && isset($_POST['password'])) {
        $nama = $_POST['nama'];
        $password = $_POST['password'];

        // Sanitasi input
        $nama = htmlspecialchars($nama);
        $password = htmlspecialchars($password);

        // Periksa apakah username dan password cocok
        if ($nama === "adminhebat" && $password === "admin1976") {
            // Set session jika login berhasil
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_menu.php"); // Redirect ke halaman admin_menu.php
            exit();
        } else {
            // Set session untuk error message jika login gagal
            $_SESSION['error_message'] = "Nama atau password salah!";
            header("Location: validasi_admin.php"); // Kembali ke halaman login
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Admin</title>
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
        <form action="validasi_admin.php" method="POST">
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