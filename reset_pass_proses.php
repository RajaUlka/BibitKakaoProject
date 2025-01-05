<?php
session_start();
require 'db_connection.php'; // Pastikan file ini berisi koneksi database menggunakan PDO

// Periksa apakah metode permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'reset' && isset($_POST['user_id'])) {
        // Ambil user_id
        $userId = $_POST['user_id'];

        // Reset password ke '123' dengan hashing
        $newPassword = password_hash('123', PASSWORD_DEFAULT);

        // Query untuk mengupdate password di database
        $query = "UPDATE user SET pass = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($query);
        
        if ($stmt) {
            $stmt->execute([$newPassword, $userId]);
            // Jika berhasil, arahkan kembali ke halaman dengan pesan sukses
            header('Location: register.php?status=success&message=Password%20reset%20successful');
            exit();
        } else {
            // Jika gagal, tampilkan pesan error
            echo "Database update failed.";
        }
    }
}
?>
