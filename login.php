<?php
session_start();
require 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]); // Biar aman dari XSS
    $password = $_POST["password"];

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"]; // Simpan ID user di session
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];
            header("Location: produk.php");
            exit;
        } else {
            echo "<script>            
            Swal.fire({
                title: 'Gagal!',
                text: 'Password Nya Salah Bos!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href='login.php';
                }
            });</script>";
        }
    } else {
        echo "<script>            
        Swal.fire({
                title: 'Gagal!',
                text: 'Username Nya Ga Ketemu Bre!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href='login.php';
                }
            });</script>";
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>RacaMarket</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Belum punya akun?</a>
    </div>
<?php require 'footer.php'; ?>
