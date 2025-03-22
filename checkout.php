<?php
require 'header.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Anda belum login. <a href='login.php'>Login</a>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["checkout"])) {
    $user_id = $_SESSION["user_id"];

    $cart_items = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
    if ($cart_items->num_rows === 0) {
        die("Keranjang kosong. <a href='index.php'>Belanja lagi</a>");
    }

    $conn->begin_transaction();

    try {
        $total_harga = 0;

        while ($item = $cart_items->fetch_assoc()) {
            $product_id = $item["product_id"];
            $quantity = $item["quantity"];

            $produk = $conn->query("SELECT nama, harga, stok FROM produk WHERE id = $product_id")->fetch_assoc();
            if (!$produk || $produk["stok"] < $quantity) {
                throw new Exception("Stok produk habis atau tidak cukup!");
            }

            $nama_produk = $conn->real_escape_string($produk["nama"]);
            $harga_produk = $produk["harga"];
            $subtotal = $harga_produk * $quantity;
            $total_harga += $subtotal;

            $insert_transaksi = $conn->query("INSERT INTO transaksi (user_id, produk, harga, produk_id, jumlah, total_harga, created_at) 
                VALUES ($user_id, '$nama_produk', $harga_produk, $product_id, $quantity, $subtotal, NOW())");

            if (!$insert_transaksi) {
                throw new Exception("Gagal menyimpan transaksi!");
            }

            $update_stok = $conn->query("UPDATE produk SET stok = stok - $quantity WHERE id = $product_id");
            if (!$update_stok) {
                throw new Exception("Gagal update stok produk!");
            }
        }

        $delete_cart = $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        if (!$delete_cart) {
            throw new Exception("Gagal menghapus keranjang!");
        }

        $conn->commit();

        header("Location: success.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Terjadi kesalahan saat checkout: " . $e->getMessage());
    }
}
?>
