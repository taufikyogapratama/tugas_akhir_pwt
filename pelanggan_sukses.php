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

        body{
            background-color: #4CAF50;
        }

        main{
            width: 643px;
            height: 432px;
            background-color: white;
            display: flex;
            justify-content: center;
            flex-direction: column;
            border-radius: 20px;
            margin-left: auto;
            margin-right: auto; 
            margin-top: 150px;
        }

        main h1{
            text-align: center;
            margin-bottom: 50px;
        }

        main button{
            padding: 7px 20px;
            background-color: red;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            width: 100px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <main>
        <h1>Pesanan Anda berhasil ditambahkan, mohon menunggu pesanan anda dan jangan lupa bayar</h1>
        <button onclick="window.location.href='masuk.php'">Kembali</button>
    </main>
</body>
</html>