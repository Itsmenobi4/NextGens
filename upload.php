<?php
require 'header.php';
function upload() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    if (!in_array($ekstensiGambar, $ekstensiValid)) {
        echo "<script>alert('Yang diupload bukan gambar!');</script>";
        return false;
    }

    if ($ukuranFile > 2000000) { 
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    $namaBaru = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, 'img/' . $namaBaru);

    return $namaBaru;
}
?>
