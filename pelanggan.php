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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .menu-list {
            display: flex;
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
            bottom: 30px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout button:hover {
            background-color: #45a049;
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

            cart.forEach(item => {
                const cartItemDiv = document.createElement('div');
                cartItemDiv.className = 'cart-item';

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
        }

        function checkout() {
            // Pastikan keranjang tidak kosong
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
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
        <h1>Silahkan pilih menu</h1>
        <button onclick="window.location.href='masuk.php'">Kembali</button>
    </header>
    <form action="pelanggan.php" method="POST" id="customerForm">
        <label for="nama">Masukkan Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="no_meja">Masukkan No Meja:</label><br>
        <input type="number" id="no_meja" name="no_meja" required><br>
    </form>
        
    <div class="Menu_dan_cart">
        <h1>Menu Restoran</h1>
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
            <div class="checkout">
                <button type="button" onclick="checkout()">Checkout</button>
            </div>
        </div>
    </div>
</body>
</html>