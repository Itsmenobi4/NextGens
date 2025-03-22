<?php
require 'connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NextGens</title>
    <link rel="website icon" type="png" href="Logo.png" style="border-radius: 12px;">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>    
<body>
    <div class="container">
    <nav class="flex items-center justify-between bg-purple-600 p-4 shadow-md w-full fixed top-0 left-0 z-50">
    <div class="text-white font-bold text-lg px-4">NextGens</div>
    <ul class="flex items-center space-x-6 text-white px-4">
        <li><a href="produk.php" class="hover:underline">Home</a></li>
        <li><a href="index.php" class="hover:underline">Produk</a></li>
        <?php if (isset($_SESSION["login"])) : ?>
            <li><a href="riwayat.php" class="hover:underline">Riwayat Transaksi</a></li>
            <li><a href="setting.php" class="hover:underline">Pengaturan</a></li>
            <li>
                <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-all duration-300">
                    Logout
                </button>
            </li>
        <?php else : ?>
            <li><a href="login.php" class="hover:underline">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="mt-16"></div> 


    <script>
    function logout() {
        Swal.fire({
            title: "Yakin ingin logout?",
            text: "Anda harus login kembali jika keluar.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, logout!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    }
    </script>
</body>
</html>
