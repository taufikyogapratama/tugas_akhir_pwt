<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warmindo masuk</title>
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
            width: 230px;
            height: 200px;
            background-color: #4CAF50;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 130px auto 0 auto;
        }

        main h3{
            color: white;
        }

        main section .btn{
            display: flex;
            flex-direction: column;
            margin-top: 30px
        }

        main section .btn .btn-admin{
            margin-top: 10px;
        }

        main section .btn button{
            padding: 8px 0;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Selamat Datang Di Warmindo</h1>
    </header>
    <main>
        <section>
            <h3>Masuk sebagai?</h3>
            <div class="btn">
                <button onclick="window.location.href='pelanggan.php'">Pelanggan</button>
                <button class="btn-admin" onclick="window.location.href='validasi_admin.php'">Admin</button>
            </div>
        </section>
    </main>
</body>
</html>