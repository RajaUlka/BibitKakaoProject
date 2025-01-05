<?php
session_start();
require 'db_connection.php';

// Cek apakah user sudah login, jika belum, redirect ke login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query untuk mengambil device_id berdasarkan user_id yang login
$query_device = $pdo->prepare("SELECT device_id FROM device WHERE user_id = :user_id");
$query_device->execute(['user_id' => $user_id]);
$device_ids = $query_device->fetchAll(PDO::FETCH_COLUMN);

if (empty($device_ids)) {
    echo json_encode(['error' => 'No device found for this user']);
    exit();
}

// Ambil data realtime berdasarkan device_id
$query_realtime = $pdo->prepare("SELECT temperature, soil_humidity, light_intensity FROM realtime_monitoring WHERE device_id IN (" . implode(',', $device_ids) . ") ORDER BY action_timestamp DESC LIMIT 1");
$query_realtime->execute();
$data_realtime = $query_realtime->fetch(PDO::FETCH_ASSOC);

if ($data_realtime) {
    echo json_encode($data_realtime);
} else {
    echo json_encode(['error' => 'No realtime data available']);
}
?>
