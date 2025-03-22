<?php
require 'connect.php';

$id = $_GET["id"];
$query = "DELETE  FROM produk WHERE id = $id";
$conn->query($query);
header("Location: index.php");
exit;
?>