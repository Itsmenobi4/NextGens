<?php
require 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["user_id"];

// Tambah produk ke keranjang
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $product_id = intval($_POST["product_id"]);
    $quantity = 1;

    $cek_cart = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $cek_cart->bind_param("ii", $user_id, $product_id);
    $cek_cart->execute();
    $cek_cart->store_result();

    if ($cek_cart->num_rows > 0) {
        $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $update_cart->bind_param("ii", $user_id, $product_id);
        $update_cart->execute();
    } else {
        $insert_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_cart->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_cart->execute();
    }

    echo "<p>Produk berhasil ditambahkan ke keranjang!</p>";
}

// Hapus produk dari keranjang
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_from_cart"])) {
    $cart_id = intval($_POST["cart_id"]);

    $delete_cart = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $delete_cart->bind_param("ii", $cart_id, $user_id);
    $delete_cart->execute();

    echo "<p>Produk berhasil dihapus dari keranjang!</p>";
}

// Ambil item dari keranjang
$query_cart = $conn->prepare("SELECT cart.id as cart_id, produk.id as product_id, produk.nama, produk.harga, cart.quantity 
                              FROM cart 
                              JOIN produk ON cart.product_id = produk.id
                              WHERE cart.user_id = ?");
$query_cart->bind_param("i", $user_id);
$query_cart->execute();
$cart_items = $query_cart->get_result();
?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">🛒 Keranjang Belanja</h2>

    <?php if ($cart_items->num_rows > 0): ?>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Produk</th>
                    <th class="border p-2">Harga</th>
                    <th class="border p-2">Jumlah</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $cart_items->fetch_assoc()): ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($item["nama"]) ?></td>
                        <td class="border p-2">Rp <?= number_format($item["harga"]) ?></td>
                        <td class="border p-2"><?= $item["quantity"] ?></td>
                        <td class="border p-2">
                            <form method="POST">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item["cart_id"]) ?>">
                                <button type="submit" name="remove_from_cart" class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Form Checkout Tanpa JavaScript -->
        <form action="checkout.php" method="post">
            <button type="submit" name="checkout" class="bg-green-500 text-white px-4 py-2 rounded-md mt-4 hover:bg-green-600">Checkout</button>
        </form>

    <?php else: ?>
        <p class="text-gray-600">Keranjang masih kosong.</p>
    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
