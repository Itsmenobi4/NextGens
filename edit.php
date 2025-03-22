 <?php
require 'header.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "<script>alert('ID produk tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
$produk = mysqli_fetch_assoc($result);
if (!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

function upload()
{
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $tmpName = $_FILES['foto']['tmp_name'];
    $error = $_FILES['foto']['error'];

    if ($error === 4) {
        return false;
    }
    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    if (!in_array($ekstensiFile, $ekstensiValid)) {
        echo "<script>alert('File harus berupa gambar (jpg, jpeg, png)!');</script>";
        return false;
    }

    if ($ukuranFile > 2000000) {
        echo "<script>alert('Ukuran gambar terlalu besar! (maks 2MB)');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensiFile;
    $pathTujuan = 'uploads/' . $namaFileBaru;

    if (move_uploaded_file($tmpName, $pathTujuan)) {
        return $pathTujuan;
    } else {
        echo "<script>alert('Gagal mengupload gambar!');</script>";
        return false;
    }
}

function edit($id, $data)
{
    global $conn;
    $nama = htmlspecialchars($data["nama"]);
    $harga = htmlspecialchars($data["harga"]);
    $stok = htmlspecialchars($data["stok"]);

    if ($_FILES['foto']['error'] === 4) {
        $foto = $data['foto_lama'];
    } else {
        $foto = upload();
        if (!$foto) {
            return false;
        }
    }

    $query = "UPDATE produk SET nama = '$nama', harga = '$harga', stok = '$stok', foto = '$foto' WHERE id = '$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

if (isset($_POST['submit'])) {
    if (edit($id, $_POST) > 0) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui!');</script>";
    }
}
?>

<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">✏️ Edit Produk</h2>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $produk['id']; ?>">
        <input type="hidden" name="foto_lama" value="<?= $produk['foto']; ?>">

        <label class="block font-medium text-gray-700" for="nama">Nama Produk:</label>
        <input type="text" name="nama" id="nama" value="<?= $produk['nama']; ?>" required class="w-full p-2 border rounded-md mb-3">

        <label class="block font-medium text-gray-700" for="harga">Harga:</label>
        <input type="text" name="harga" id="harga" value="<?= $produk['harga']; ?>" required class="w-full p-2 border rounded-md mb-3">

        <label class="block font-medium text-gray-700" for="stok">Stok:</label>
        <input type="text" name="stok" id="stok" value="<?= $produk['stok']; ?>" required class="w-full p-2 border rounded-md mb-3">

        <label class="block font-medium text-gray-700" for="foto">Foto Produk:</label>
        <input type="file" name="foto" id="foto" class="w-full p-2 border rounded-md mb-3">
        <br>
        <div class="mb-3">
            <img src="<?= $produk['foto']; ?>" class="rounded-lg border w-32 h-32 object-cover shadow-md">
        </div>

        <div class="flex space-x-3">
            <button type="submit" name="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                Simpan
            </button>
            <a href="index.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
