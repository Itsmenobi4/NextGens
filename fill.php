<?php
require 'connect.php';

function fill($id) {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
    return mysqli_fetch_assoc($result);
}
?>
