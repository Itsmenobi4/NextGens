<?php
require 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (!isset($conn)) {
    die("Error: Koneksi database tidak ditemukan.");
}

$user_id = $_SESSION["user_id"];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("Error: User tidak ditemukan.");
}

$user = mysqli_fetch_assoc($result);

$user_username = $user["username"] ?? "";
$user_email = $user["email"] ?? "";

if (isset($_POST["update_profile"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    $query = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION["username"] = $username;
        $_SESSION["flash_message"] = "Profil berhasil diperbarui!";
        $_SESSION["flash_type"] = "success";
    } else {
        $_SESSION["flash_message"] = "Terjadi kesalahan saat update: " . mysqli_error($conn);
        $_SESSION["flash_type"] = "error";
    }
    
    header("Location: setting.php");
    exit;
}

if (isset($_POST["update_password"])) {
    $password_lama = $_POST["password_lama"];
    $password_baru = $_POST["password_baru"];
    $password_konfirmasi = $_POST["password_konfirmasi"];

    if (!password_verify($password_lama, $user["password"])) {
        $_SESSION["flash_message"] = "Password lama salah!";
        $_SESSION["flash_type"] = "error";
    } elseif ($password_baru !== $password_konfirmasi) {
        $_SESSION["flash_message"] = "Konfirmasi password tidak cocok!";
        $_SESSION["flash_type"] = "error";
    } else {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$password_hash' WHERE id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION["flash_message"] = "Password berhasil diperbarui!";
            $_SESSION["flash_type"] = "success";
        } else {
            $_SESSION["flash_message"] = "Terjadi kesalahan saat update password: " . mysqli_error($conn);
            $_SESSION["flash_type"] = "error";
        }
    }

    header("Location: setting.php");
    exit;
}
?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">⚙️ Pengaturan</h2>

    <?php if (isset($_SESSION["flash_message"])): ?>
        <script>
            Swal.fire({
                title: "<?= $_SESSION['flash_type'] == 'success' ? 'Berhasil!' : 'Gagal!' ?>",
                text: "<?= $_SESSION['flash_message'] ?>",
                icon: "<?= $_SESSION['flash_type'] ?>"
            });
        </script>
        <?php unset($_SESSION["flash_message"], $_SESSION["flash_type"]); ?>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-md">
        <label class="block mb-2">Nama:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user_username) ?>" class="border px-3 py-2 rounded-md w-full mb-3">
        
        <label class="block mb-2">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" class="border px-3 py-2 rounded-md w-full mb-3">
        
        <button type="submit" name="update_profile" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update Profil</button>
    </form>
    
    <h3 class="text-xl font-semibold mt-6">🔒 Ubah Password</h3>
    <form method="POST" class="bg-white p-6 rounded-lg shadow-md mt-4">
        <label class="block mb-2">Password Lama:</label>
        <input type="password" name="password_lama" class="border px-3 py-2 rounded-md w-full mb-3">
        
        <label class="block mb-2">Password Baru:</label>
        <input type="password" name="password_baru" class="border px-3 py-2 rounded-md w-full mb-3">
        
        <label class="block mb-2">Konfirmasi Password:</label>
        <input type="password" name="password_konfirmasi" class="border px-3 py-2 rounded-md w-full mb-3">
        
        <button type="submit" name="update_password" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Update Password</button>
    </form>
</div>

<?php require 'footer.php'; ?>
