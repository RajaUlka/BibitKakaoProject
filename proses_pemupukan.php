<?php
require 'db_connection.php'; // Pastikan file ini menghubungkan ke database

// Pastikan session dimulai
session_start();

// Cek jika data dikirimkan melalui form
if (isset($_POST['fertilization_dates']) && !empty($_POST['fertilization_dates'])) {
    // Ambil array tanggal pemupukan dari form
    $fertilization_dates = $_POST['fertilization_dates'];

    // Ambil ID pengguna yang sedang login
    if (!isset($_SESSION['user_id'])) {
        header("Location: settings_fertilization.php?error=User ID tidak ditemukan, pastikan Anda login.");
        exit();
    }
    $user_id = $_SESSION['user_id']; // ID pengguna yang sedang login

    // Cek apakah pengguna memiliki perangkat terdaftar
    $query_device_check = $pdo->prepare("SELECT device_id FROM device WHERE user_id = :user_id");
    $query_device_check->execute(['user_id' => $user_id]);
    $device = $query_device_check->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        // Jika tidak ada perangkat terdaftar, tampilkan pesan pop-up dan arahkan kembali ke halaman yang sesuai
        header("Location: settings_fertilization.php?error=Anda tidak memiliki perangkat terdaftar. Silakan tambahkan perangkat terlebih dahulu.");
        exit();
    }

    // Ambil device_id pertama yang ditemukan
    $device_id = $device['device_id']; // Device ID yang terdaftar

    // Mulai transaksi agar kedua insert dilakukan bersama
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    try {
        // Persiapkan query untuk insert ke tabel pemupukan (jadwal pemupukan)
        $sql_schedule = $pdo->prepare("INSERT INTO pemupukan (device_id, schedule_date) VALUES (:device_id, :schedule_date)");

        // Persiapkan query untuk insert ke tabel histori_pemupukan (riwayat pemupukan)
        $sql_history = $pdo->prepare("INSERT INTO histori_pemupukan (device_id, action_type, schedule_date, updated_by) VALUES (:device_id, :action_type, :schedule_date, :updated_by)");

        // Iterasi untuk setiap tanggal pemupukan yang dipilih
        foreach ($fertilization_dates as $date) {
            // Set action type untuk pemupukan yang dijadwalkan
            $action_type = 'scheduled';

            // Bind parameter untuk query pemupukan (jadwal pemupukan)
            $sql_schedule->bindParam(':device_id', $device_id, PDO::PARAM_INT);
            $sql_schedule->bindParam(':schedule_date', $date, PDO::PARAM_STR);

            // Bind parameter untuk query histori pemupukan (riwayat pemupukan)
            $sql_history->bindParam(':device_id', $device_id, PDO::PARAM_INT);
            $sql_history->bindParam(':action_type', $action_type, PDO::PARAM_STR);
            $sql_history->bindParam(':schedule_date', $date, PDO::PARAM_STR);
            $sql_history->bindParam(':updated_by', $user_id, PDO::PARAM_INT);

            // Eksekusi query pemupukan (jadwal pemupukan)
            $sql_schedule->execute();

            // Eksekusi query histori pemupukan (riwayat pemupukan)
            $sql_history->execute();
        }

        // Commit transaksi jika semua query berhasil
        $pdo->commit();
        header("Location: settings_fertilization.php?success=Semua data pemupukan berhasil disimpan.");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        $pdo->rollBack();
        $error_message = urlencode("Terjadi kesalahan: " . $e->getMessage());
        header("Location: settings_fertilization.php?error=$error_message");
        exit();
    }
} else {
    header("Location: settings_fertilization.php?error=Tidak ada tanggal yang dipilih untuk pemberian pupuk.");
    exit();
}
?>
