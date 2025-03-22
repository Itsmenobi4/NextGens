<?php
session_start();
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login dulu!'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data transaksi
$query = "SELECT t.id, p.nama AS produk, t.harga, t.jumlah, t.created_at 
          FROM transaksi t
          JOIN produk p ON t.produk_id = p.id
          WHERE t.user_id = ?
          ORDER BY t.created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">📜 Riwayat Transaksi</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden shadow-md">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Produk</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Jumlah</th>
                    <th class="p-3 border">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) : 
                ?>
                <tr class="border border-gray-300">
                    <td class="p-3 border text-center"><?= $i++; ?></td>
                    <td class="p-3 border"><?= htmlspecialchars($row['produk']); ?></td>
                    <td class="p-3 border">Rp <?= number_format($row['harga'] * $row['jumlah'], 0, ',', '.'); ?></td>
                    <td class="p-3 border text-center"><?= $row['jumlah']; ?></td>
                    <td class="p-3 border text-center"><?= date("d M Y H:i", strtotime($row['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>

                <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                    <td colspan="5" class="text-center p-4">Belum ada transaksi</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        <a href="index.php" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition">Kembali</a>
    </div>
</div>

<?php require 'footer.php'; ?>
