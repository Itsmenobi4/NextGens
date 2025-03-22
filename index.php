<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require 'header.php';

$produk = mysqli_query($conn, "SELECT * FROM produk");

if (isset($_POST["cari"])) {
    $keyword = mysqli_real_escape_string($conn, $_POST["keyword"]);
    $produk = mysqli_query($conn, "SELECT * FROM produk WHERE nama LIKE '%$keyword%'");
}

if (isset($_POST["add_to_cart"])) {
    $user_id = $_SESSION["user_id"];
    $product_id = $_POST["product_id"];

    // Cek apakah produk sudah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");

    if (mysqli_num_rows($cek) > 0) {
        $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
        if (!mysqli_query($conn, $updateQuery)) {
            die("Error Update: " . mysqli_error($conn));
        }
    } else {
        $insertQuery = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
        if (!mysqli_query($conn, $insertQuery)) {
            die("Error Insert: " . mysqli_error($conn));
        }
    }

    echo "<script>Swal.fire('Berhasil!', 'Produk ditambahkan ke keranjang!', 'success');</script>";
}
?>

<div class="flex justify-between items-center mb-5">
    <form method="POST" class="flex gap-2 bg-white p-3 rounded-lg shadow-md">
        <input type="text" name="keyword" placeholder="Cari produk..." class="border px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit" name="cari" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Cari</button>
    </form>
    
    <div class="flex gap-3">
        <a href="tambah_produk.php">
            <button class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition">Tambah Produk</button>
        </a>
        <a href="beli.php">
            <button class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition">Beli</button>
        </a>
        <a href="keranjang.php">
            <button class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">🛒 Keranjang</button>
        </a>
    </div>
</div>

<h2 class="text-2xl font-semibold text-indigo-700 mb-3">🎁 Daftar Produk</h2>

<table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden shadow-md">
    <tr class="bg-indigo-600 text-white">
        <th class="p-3 border">No.</th>
        <th class="p-3 border">Aksi</th>
        <th class="p-3 border">Nama</th>
        <th class="p-3 border">Harga</th>
        <th class="p-3 border">Stok</th>
        <th class="p-3 border">Foto</th>
    </tr>

    <?php $i = 1; ?>
    <?php if (mysqli_num_rows($produk) == 0) : ?>
        <tr>
            <td colspan="6" class="text-center p-4">Tidak ada produk</td>
        </tr>
    <?php else : ?>
        <?php while ($row = mysqli_fetch_assoc($produk)) : ?>
        <tr class="border border-gray-300">
            <td class="p-3 border"><?= $i++; ?></td>
            <td class="p-3 border">
                <a href="edit.php?id=<?= $row['id']; ?>" class="bg-purple-500 text-white px-4 py-2 rounded-md text-lg font-semibold hover:bg-purple-600 transition">Edit</a>
                <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition" onclick="hapusProduk(<?= $row['id']; ?>)">Hapus</button>

                <!-- Form Tambah ke Keranjang -->
                <form method="POST" class="inline">
                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="add_to_cart" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition mt-2">Tambah ke Keranjang</button>
                </form>
            </td>
            <td class="p-3 border"><?= $row["nama"]; ?></td>
            <td class="p-3 border">Rp <?= number_format($row["harga"]); ?></td>
            <td class="p-3 border"><?= $row["stok"]; ?></td>
            <td class="p-3 border">
                <img src="<?= $row["foto"]; ?>" alt="Produk" class="w-20 h-20 object-cover rounded-md">
            </td>
        </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function hapusProduk(id) {
    Swal.fire({
        title: 'Yakin mau hapus?',
        text: "Kenangan ini ga bisa balik lagi lhoo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'iyaa yakin!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete.php?id=' + id;
        }
    });
}

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

<?php require 'footer.php'; ?>
