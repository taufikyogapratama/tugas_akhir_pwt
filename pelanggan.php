<?php
include "database.php";

$sql_read = "SELECT * FROM menu";
$result = $conn->query($sql_read);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_pelanggan = $_POST['nama'];
    $no_meja = $_POST['no_meja'];
    $cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

    // Pastikan data lengkap
    if (!$nama_pelanggan || !$no_meja || empty($cart)) {
        die("Form tidak lengkap atau keranjang kosong.");
    }

    // Simpan data pelanggan ke database
    $sql_insert_pelanggan = "INSERT INTO pelanggan (nama_pelanggan, no_meja) VALUES ('$nama_pelanggan', '$no_meja')";
    if ($conn->query($sql_insert_pelanggan)) {
        // Ambil ID pelanggan terakhir yang dimasukkan
        $pelanggan_id = $conn->insert_id;

        // Simpan setiap item keranjang ke tabel pesanan
        foreach ($cart as $item) {
            $menu_id = $item['id'];
            $quantity = $item['quantity'];
            $sql_insert_order = "INSERT INTO pesanan (id_pelanggan, id_menu, jumlah) VALUES ('$pelanggan_id', '$menu_id', '$quantity')";
            $conn->query($sql_insert_order);
        }

        header("Location: pelanggan_sukses.php");
        exit();
    } else {
        echo "Gagal menyimpan data pelanggan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pelanggan</title>
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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 400px;
            margin: 10px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            background-color: white;
        }

        form input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .judul{
            text-align: center;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .form-description {
            text-align: center;
            margin-bottom: 15px;
            color: #666;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .menu-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px;
        }
        .menu-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            width: 200px;
            text-align: center;
        }
        .menu-item button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .menu-item button:hover {
            background-color: #45a049;
        }
        .cart {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            overflow: auto;
            background-color: #f9f9f9;
            border-top: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }
        .cart h3 {
            margin: 0 0 10px;
        }
        .cart-items {
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
        }
        .cart-item span {
            flex: 1;
        }
        .cart-item button {
            padding: 5px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 1.563vw;
        }
        .cart-item button:hover {
            background-color: #e60000;
        }
        .checkout {
            text-align: center;
        }
        .checkout button {
            position: fixed;
            padding: 10px 20px;
            margin-left: auto;
            margin-right: auto;
            bottom: 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout button:hover {
            background-color: #45a049;
        }

        .div-kosong{
            height: 200px;
        }

        @media only screen and (max-width: 768px) {
            header p{
                font-size: 1.25rem;
            }

            header button{
                font-size: 0.8rem;
                padding: 7px 10px;
            }

            form {
                max-width: 400px;
                margin: 6px auto;
                background: #fff;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            form h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #4CAF50;
            }

            form label {
                display: block;
                margin-bottom: 2px;
                font-weight: bold;
                font-size: 0.8rem;
            }

            form input, select {
                width: 100%;
                padding: 5px;
                margin-bottom: 5px;
                border: 1px solid #ccc;
                border-radius: 10px;
                font-size: 16px;
                background-color: white;
            }

            form input:focus {
                border-color: #4CAF50;
                outline: none;
                box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
            }

            form button {
                width: 100%;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 18px;
                cursor: pointer;
            }

            form button:hover {
                background-color: #45a049;
            }

            .judul{
                font-size: 1.2rem;
            }

            .menu-item {
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 9px;
                width: 170px;
                text-align: center;
            }
            .menu-item button {
                margin-top: 6px;
                padding: 3px 8px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                font-size: 0.5rem;
            }

            .menu-item h3{
                font-size: 0.8rem;
            }

            .menu-item p{
                font-size: 0.8rem;
            }

            .cart {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 170px;
                overflow: auto;
                background-color: #f9f9f9;
                border-top: 1px solid #ccc;
                padding: 10px 10px 15px 10px;
                box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
            }
            .cart h3 {
                margin: 0 0 8px;
                font-size: 1rem;
            }

            .cart-items p{
                font-size: 0.8rem;
            }
            .cart-items {
                max-height: 150px;
                overflow-y: auto;
                margin-bottom: 10px;
            }
            .cart-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #ddd;
                padding: 5px 0;
            }
            .cart-item span {
                flex: 1;
                font-size: 0.8rem;
            }
            .cart-item button {
                padding: 3px;
                background-color: #ff4d4d;
                color: white;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                margin-right: 1.563vw;
                font-size: 0.6rem;
            }

            .cart .cart-total{
                font-size: 0.8rem;
            }

            .checkout button {
                position: fixed;
                padding: 5px 15px;
                margin-left: auto;
                margin-right: auto;
                bottom: 19px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .div-kosong{
                height: 170px;
            }
        }
    </style>
    <script>
        let cart = [];

        function addToCart(menuId, menuName, menuPrice) {
            // Check if the item already exists in the cart
            let existingItem = cart.find(item => item.id === menuId);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ id: menuId, name: menuName, price: menuPrice, quantity: 1 });
            }

            renderCart();
        }

        function removeFromCart(menuId) {
            cart = cart.filter(item => item.id !== menuId);
            renderCart();
        }

        function renderCart() {
            const cartItemsDiv = document.querySelector('.cart-items');
            cartItemsDiv.innerHTML = '';

            let totalPrice = 0;

            cart.forEach(item => {
                const cartItemDiv = document.createElement('div');
                cartItemDiv.className = 'cart-item';

                totalPrice += item.price * item.quantity;
                cartItemDiv.innerHTML = `
                    <div>
                    <span>${item.name} (x${item.quantity})</span>
                    <span>Rp ${item.price * item.quantity}</span>
                    </div>
                    <button onclick="removeFromCart(${item.id})">Hapus</button>
                `;

                cartItemsDiv.appendChild(cartItemDiv);
            });

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<p>Keranjang kosong</p>';
            }

            const totalDiv = document.querySelector('.cart-total');
            totalDiv.innerHTML = `Total Harga: Rp ${totalPrice.toLocaleString('id-ID')}`;
        }

        function checkout() {
            // Pastikan keranjang tidak kosong
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const confirmOrder = confirm('Apakah Anda yakin ingin memesan menu ini?');
            if (!confirmOrder) {
                return;
            }
            // Ambil elemen form pelanggan
            const customerForm = document.getElementById('customerForm');

            // Buat input tersembunyi untuk menyisipkan data keranjang ke form
            const cartInput = document.createElement('input');
            cartInput.type = 'hidden';
            cartInput.name = 'cart'; // Nama variabel untuk dikirim ke PHP
            cartInput.value = JSON.stringify(cart); // Data keranjang dalam format JSON
            customerForm.appendChild(cartInput);

            // Submit form pelanggan
            customerForm.submit();
        }

    </script>
</head>
<body>
    <header>
        <p>Silahkan pilih menu</p>
        <button onclick="window.location.href='/tugas_akhir_pwt'">Kembali</button>
    </header>
    <form action="pelanggan.php" method="POST" id="customerForm">
        <label for="nama">Masukkan Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="no_meja">Masukkan No Meja:</label><br>
        <select id="no_meja" name="no_meja"> 
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="4">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
        </select>
    </form>
        
    <div class="Menu_dan_cart">
        <h1 class="judul">Menu Restoran</h1>
        <div class="menu-list">
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<h3>" . $row['nama_makanan'] . "</h3>";
                    echo "<p>Rp " . number_format($row['harga'], 0, ',', '.') . "</p>";
                    echo "<button onclick=\"addToCart(" . $row['id_menu'] . ", '" . $row['nama_makanan'] . "', " . $row['harga'] . ")\">Tambah</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>Tidak ada menu tersedia</p>";
            }
            ?>
        </div> 
        <div class="cart">
            <h3>Keranjang</h3>
            <div class="cart-items">
                <p>Keranjang kosong</p>
            </div>
            <div class="cart-total" style="font-weight: bold; margin-top: 10px;">
                Total Harga: Rp 0
            </div>
            <div class="checkout">
                <button type="button" onclick="checkout()">Pesan</button>
            </div>
        </div>
    </div>
    <div class="div-kosong">

    </div>
</body>
</html>