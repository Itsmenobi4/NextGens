<?php
session_start();
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login dulu!'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];

    $stmt = mysqli_prepare($conn, "SELECT stok, harga FROM produk WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $produk_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result);

    if (!$produk) {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href='index.php';</script>";
        exit;
    }

    $stok_tersedia = $produk['stok'];
    $harga = $produk['harga'];
    $total_harga = $harga * $jumlah;

    if ($jumlah > $stok_tersedia) {
        echo "<script>           
        Swal.fire({
                title: 'Gagal!',
                text: 'Stok Nya Kurang Bos!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href='beli.php';
                }
            });</script>";
        exit;
    }

    $stok_baru = $stok_tersedia - $jumlah;
    $stmt = mysqli_prepare($conn, "UPDATE produk SET stok = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $stok_baru, $produk_id);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (user_id, produk_id, harga, jumlah, total_harga, created_at) 
                                   VALUES (?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "iiiii", $user_id, $produk_id, $harga, $jumlah, $total_harga);
    $execute = mysqli_stmt_execute($stmt);

    if ($execute) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pembelian Berhasil!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href='index.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Yah Barang Nya ga kebeli bos!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href='beli.php';
            });
        </script>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">🛒 Beli Produk</h2>

    <form action="beli.php" method="POST">
        <label class="block font-medium text-gray-700" for="produk">Pilih Produk:</label>
        <select name="produk_id" id="produk" required class="w-full p-2 border rounded-md mb-3">
            <option value="">-- Pilih Produk --</option>
            <?php
            $produk = mysqli_query($conn, "SELECT id, nama, harga, stok FROM produk WHERE stok > 0");
            while ($row = mysqli_fetch_assoc($produk)) {
                echo "<option value='{$row['id']}'>{$row['nama']} - Rp " . number_format($row['harga'], 0, ',', '.') . " (Stok: {$row['stok']})</option>";
            }
            ?>
        </select>

        <label class="block font-medium text-gray-700" for="jumlah">Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" min="1" required class="w-full p-2 border rounded-md mb-3">

        <div class="flex space-x-3">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                Beli Sekarang
            </button>
            <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Kembali ke Home
            </a>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>