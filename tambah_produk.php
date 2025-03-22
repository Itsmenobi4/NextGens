<?php
require 'header.php';

if (isset($_POST["submit"])) {
    $nama = $_POST["nama"];
    $harga = $_POST["harga"];
    $stok = $_POST["stok"];
    
    $foto = $_FILES['foto']['name'];
    $tmpName = $_FILES['foto']['tmp_name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($foto);
    move_uploaded_file($tmpName, $targetFile);

    $query = "INSERT INTO produk (nama, harga, stok, foto) VALUES ('$nama', '$harga', '$stok', '$targetFile')";
    mysqli_query($conn, $query);
    
    echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href = 'index.php';</script>";
}
?>

<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Tambah Produk</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="nama" class="block font-medium">Nama Produk:</label>
            <input type="text" name="nama" id="nama" required class="w-full border px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div>
            <label for="harga" class="block font-medium">Harga:</label>
            <input type="text" name="harga" id="harga" required class="w-full border px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div>
            <label for="stok" class="block font-medium">Stok:</label>
            <input type="text" name="stok" id="stok" required class="w-full border px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div>
            <label for="foto" class="block font-medium">Foto Produk:</label>
            <input type="file" name="foto" id="foto" required class="w-full border px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        
        <div class="flex gap-3">
            <button type="submit" name="submit" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition">Tambah Produk</button>
            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition" onclick="window.location.href='index.php'">Batal</button>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
