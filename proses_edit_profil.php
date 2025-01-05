<?php
session_start();
require 'db_connection.php';  // Pastikan ini adalah file yang menghubungkan ke database

// Cek jika pengguna sudah login, jika tidak redirect ke login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Membuat instance dari Profil untuk mengambil data pengguna
$profil = new Profil($pdo);

// Ambil data pengguna berdasarkan user_id
$user = $profil->getUserById($user_id);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {


    // Mengambil data dari form
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $pass = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;

    // Jika password diubah
    if ($pass) {
        // Hash password baru
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        
        // Query untuk update data user dengan password baru
        $query = "UPDATE user SET email = :email, pass = :pass WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':pass', $pass);
    } else {
        // Query untuk update data user tanpa password
        $query = "UPDATE user SET email = :email WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
    }

    // Binding parameter untuk user_id
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_id', $user_id);

    // Menjalankan query
    if ($stmt->execute()) {
        // Berhasil update, redirect kembali ke edit_profil.php dengan pesan sukses
        $_SESSION['success_message'] = "Profil berhasil diperbarui!";
        header('Location: edit_profil.php');
        exit();
    } else {
        // Gagal update, redirect kembali dengan pesan error
        $_SESSION['error_message'] = "Gagal memperbarui profil.";
        header('Location: edit_profil.php');
        exit();
    }
}
?>
