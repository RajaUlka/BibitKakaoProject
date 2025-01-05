<?php
require 'db_connection.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek jika user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: settings_watering.php?error=User tidak login.");
        exit();
    }

    $user_id = $_SESSION['user_id']; // ID user yang login

    // Cek apakah user memiliki device
    if (!userHasDevice($pdo, $user_id)) {
        header("Location: settings_watering.php?error=Anda tidak memiliki device untuk disetel.");
        exit();
    }

    // Ambil device_id milik user
    $query_device = $pdo->prepare("SELECT device_id FROM device WHERE user_id = :user_id LIMIT 1");
    $query_device->execute(['user_id' => $user_id]);
    $device_id = $query_device->fetchColumn();

    if (!$device_id) {
        header("Location: settings_watering.php?error=Device ID tidak ditemukan.");
        exit();
    }

    // Ambil data dari form
    $min_humidity = $_POST['min_humidity'] ?? null;
    $max_humidity = $_POST['max_humidity'] ?? null;
    $schedule_start = $_POST['schedule_start'] ?? null;
    $schedule_end = $_POST['schedule_end'] ?? null;

    if (empty($min_humidity) || empty($max_humidity) || empty($schedule_start) || empty($schedule_end)) {
        header("Location: settings_watering.php?error=Semua field harus diisi.");
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Insert ke tabel penyiraman
        $sql_watering = $pdo->prepare("
            INSERT INTO penyiraman 
            (device_id, min_humidity, max_humidity, schedule_start, schedule_end, last_updated) 
            VALUES (:device_id, :min_humidity, :max_humidity, :schedule_start, :schedule_end, NOW())
        ");
        $sql_watering->execute([
            ':device_id' => $device_id,
            ':min_humidity' => $min_humidity,
            ':max_humidity' => $max_humidity,
            ':schedule_start' => $schedule_start,
            ':schedule_end' => $schedule_end
        ]);

        // Insert ke tabel histori_penyiraman
        $sql_history = $pdo->prepare("
            INSERT INTO histori_penyiraman 
            (device_id, min_humidity, max_humidity, schedule_start, schedule_end, change_type, action_timestamp, updated_by) 
            VALUES (:device_id, :min_humidity, :max_humidity, :schedule_start, :schedule_end, 'automatic', NOW(), :updated_by)
        ");
        $sql_history->execute([
            ':device_id' => $device_id,
            ':min_humidity' => $min_humidity,
            ':max_humidity' => $max_humidity,
            ':schedule_start' => $schedule_start,
            ':schedule_end' => $schedule_end,
            ':updated_by' => $user_id
        ]);

        $pdo->commit();
        header("Location: settings_watering.php?success=Pengaturan penyiraman berhasil disimpan.");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = urlencode("Terjadi kesalahan: " . $e->getMessage());
        header("Location: settings_watering.php?error=$error_message");
        exit();
    }
} else {
    header("Location: settings_watering.php?error=Tidak ada data yang dikirim.");
    exit();
}
