<?php
session_start();
include 'header.php';

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login dulu!'); window.location.href='login.php';</script>";
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
</head>
<body>
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
    <h1>Halo, <?= htmlspecialchars($username); ?>! 👋</h1>
    <p>Selamat datang di NextGens</p>
    <p>Anda bisa membeli gadget dengan kualitas yang terbail.</p>
    <p>Semua Gadget tersedia di sini!</p>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
