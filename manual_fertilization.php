<?php
require 'db_connection.php'; // Pastikan file ini menghubungkan ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = $_POST['device_id'] ?? null;

    if ($device_id) {
        $stmt = $pdo->prepare("INSERT INTO fertilizer_manual_log (device_id) VALUES (?)");
        $stmt->bindParam(1, $device_id);
        $stmt->execute();
        echo "Pupuk berhasil diberikan secara manual.";
    } else {
        echo "Device ID tidak valid.";
    }
}
?>
