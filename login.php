

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }
        .container {
            display: flex;
            width: 700px;
            height: 450px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }
        .left {
            background-color: green;
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .left div {
            background-color: #f0f4f8;
            width: 200px;
            height: 250px;
            border-radius: 0 25px 0 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left img.logo {
            max-width: 150px;
        }
        .right {
            background-color: white;
            width: 60%;
            padding: 40px 30px;
        }
        .right h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 25px;
            background-color: #ddd;
            font-size: 14px;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
        }
        .right form a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .right form a:hover {
            color: #19692c;
            text-decoration: underline;
        }
        
       button {
    width: 200px;
    padding: 12px;
    margin: 20px auto 0; /* Atas | Kanan-Kiri (auto) | Bawah */
    display: block; /* Wajib untuk margin auto */
    border: none;
    border-radius: 25px;
    background-color: #ccc;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
}
        button:hover {
            background-color: #bbb;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left">
        <div><img src="aset/logo.png" alt="Logo" class="logo"></div>
    </div>
    <div class="right">
        <h2>Login</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="proses_login.php" method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>
