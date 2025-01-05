<?php
require 'db_connection.php'; // Pastikan file ini menghubungkan ke database

// Pastikan session dimulai
session_start();

// Cek jika data dikirimkan melalui form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $manual_control = isset($_POST['manual_control']) ? $_POST['manual_control'] : 0; // Nilai manual control (0 atau 1)
    $schedule_start = $_POST['schedule_start']; // Waktu mulai pencahayaan
    $schedule_end = $_POST['schedule_end']; // Waktu akhir pencahayaan

    // Validasi pengguna login
    if (!isset($_SESSION['user_id'])) {
        header("Location: settings_lighting.php?error=User ID tidak ditemukan, pastikan Anda login.");
        exit();
    }
    $user_id = $_SESSION['user_id']; // ID pengguna yang sedang login

    // Cek apakah pengguna memiliki perangkat terdaftar
    $query_device_check = $pdo->prepare("SELECT device_id FROM device WHERE user_id = :user_id");
    $query_device_check->execute(['user_id' => $user_id]);
    $device = $query_device_check->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        // Jika tidak ada perangkat terdaftar, tampilkan pesan pop-up dan arahkan kembali ke settings_lighting.php
        header("Location: settings_lighting.php?error=Anda tidak memiliki perangkat terdaftar. Silakan tambahkan perangkat terlebih dahulu.");
        exit();
    }

    // Ambil device_id pertama yang ditemukan
    $device_id = $device['device_id'];

    // Mulai transaksi agar kedua insert dilakukan bersama
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    try {
        // Persiapkan query untuk insert ke tabel pencahayaan
        $sql_lighting = $pdo->prepare("
            INSERT INTO pencahayaan (device_id, manual_control, schedule_start, schedule_end, last_updated) 
            VALUES (:device_id, :manual_control, :schedule_start, :schedule_end, NOW())
        ");

        // Persiapkan query untuk insert ke tabel history_pencahayaan
        $sql_history = $pdo->prepare("
            INSERT INTO histori_pencahayaan (device_id, manual_control, schedule_start, schedule_end, change_type, action_timestamp, updated_by) 
            VALUES (:device_id, :manual_control, :schedule_start, :schedule_end, :change_type, NOW(), :updated_by)
        ");

        // Set nilai `change_type` untuk histori pencahayaan
        $change_type = 'scheduled'; // Misalnya, "scheduled" untuk perubahan jadwal

        // Bind parameter untuk tabel pencahayaan
        $sql_lighting->bindParam(':device_id', $device_id, PDO::PARAM_INT);
        $sql_lighting->bindParam(':manual_control', $manual_control, PDO::PARAM_INT);
        $sql_lighting->bindParam(':schedule_start', $schedule_start, PDO::PARAM_STR);
        $sql_lighting->bindParam(':schedule_end', $schedule_end, PDO::PARAM_STR);

        // Bind parameter untuk tabel history_pencahayaan
        $sql_history->bindParam(':device_id', $device_id, PDO::PARAM_INT);
        $sql_history->bindParam(':manual_control', $manual_control, PDO::PARAM_INT);
        $sql_history->bindParam(':schedule_start', $schedule_start, PDO::PARAM_STR);
        $sql_history->bindParam(':schedule_end', $schedule_end, PDO::PARAM_STR);
        $sql_history->bindParam(':change_type', $change_type, PDO::PARAM_STR);
        $sql_history->bindParam(':updated_by', $user_id, PDO::PARAM_INT);

        // Eksekusi query untuk tabel pencahayaan
        $sql_lighting->execute();

        // Eksekusi query untuk tabel history_pencahayaan
        $sql_history->execute();

        // Commit transaksi jika semua query berhasil
        $pdo->commit();
        header("Location: settings_lighting.php?success=Data pencahayaan berhasil disimpan.");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        $pdo->rollBack();
        $error_message = urlencode("Terjadi kesalahan: " . $e->getMessage());
        header("Location: settings_lighting.php?error=$error_message");
        exit();
    }
} else {
    header("Location: settings_lighting.php?error=Tidak ada data pencahayaan yang dikirim.");
    exit();
}
