<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "toko";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function query($query) {
    global $conn;
    $result = $conn->query($query);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function cari($keyword) {
    global $conn;
    $query = "SELECT * FROM produk WHERE nama LIKE '%$keyword%'";
    return query($query);
}
?>