<!-- <?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['nama']) && isset($_GET['password'])) { // Memastikan input ada
        $nama = $_GET['nama'];
        $password = $_GET['password'];

        $nama = htmlspecialchars($nama);
        $password = htmlspecialchars($password);

        if ($nama === "adminaseli123" && $password === "admin678"){
            header("Location: admin_menu.php");
        }else{
            echo "<script>alert(Maaf, Anda tidak memiliki akses untuk mengakses halaman admin)</script>";
        }
    } else {
        echo "Form belum disubmit.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="nama">Nama</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Masuk">
    </form>
    <button onclick="window.location.href='masuk.php'">Kembali</button>
</body>
</html> -->

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Perubahan: Memproses POST
    if (isset($_POST['nama']) && isset($_POST['password'])) {
        $nama = $_POST['nama']; // Perubahan: Menggunakan $_POST
        $password = $_POST['password']; // Perubahan: Menggunakan $_POST

        $nama = htmlspecialchars($nama);
        $password = htmlspecialchars($password);

        if ($nama === "adminaseli123" && $password === "admin678") {
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
</head>
<body>
    <header>
        <h1>Validasi Admin</h1>
    </header>
    <main>
        <h3>Tolong masukkan nama dan password untuk memastika bahwa Anda memang admin</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="nama">Nama</label><br>
            <input type="text" id="nama" name="nama" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Masuk">
        </form>
        <button onclick="window.location.href='masuk.php'">Kembali</button>
    </main>
</body>
</html>