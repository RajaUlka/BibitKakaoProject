<?php
require_once 'db_connection.php';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $device = new Device($pdo);

    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $device_name = $_POST['device_name'];
        $device_type = $_POST['device_type'];
        $device_status = $_POST['device_status'];
        $user_id = $_POST['user_id'];
        $created_at = date('Y-m-d H:i:s');
        $device->create($device_name, $device_type, $device_status, $user_id, $created_at);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
        $device_id = $_POST['device_id'];
        $device_name = $_POST['device_name'];
        $device_type = $_POST['device_type'];
        $device_status = $_POST['device_status'];
        $user_id = $_POST['user_id'];
        $device->update($device_id, $device_name, $device_type, $device_status, $user_id);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $device_id = $_POST['device_id'];
        $device->delete($device_id);
    }
}
