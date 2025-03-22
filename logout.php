<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function logout(id) {
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
            fetch("logout.php", { 
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ logout: true }) 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Logout Berhasil!", data.message, "success").then(() => {
                        window.location.href = "login.php"; 
                    });
                } else {
                    Swal.fire("Oops!", data.message, "error");
                }
            })
            .catch(error => {
                Swal.fire("Error!", "Terjadi kesalahan!", "error");
            });
        }
    });
}
</script>