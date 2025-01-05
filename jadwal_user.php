<?php
header('Content-Type: application/json');
require_once('db_connection.php');

// Ensure user_id is properly retrieved from session
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];  // Adjust to match your authentication system

// Query to fetch devices
$queryDevices = "SELECT device_id, device_name, device_type, device_status FROM device WHERE user_id = :user_id";
$stmtDevices = $pdo->prepare($queryDevices);
$stmtDevices->execute(['user_id' => $user_id]);
$devices = $stmtDevices->fetchAll(PDO::FETCH_ASSOC);

// Debug: log the fetched devices

// Fetch schedules for watering, lighting, and fertilization

// Query for watering schedule with join on device table
$queryWatering = "SELECT p.device_id, p.schedule_start, p.schedule_end 
                  FROM penyiraman p
                  INNER JOIN device d ON p.device_id = d.device_id
                  WHERE d.user_id = :user_id
                  ORDER BY p.schedule_start DESC LIMIT 1";

$stmtWatering = $pdo->prepare($queryWatering);
$stmtWatering->execute(['user_id' => $user_id]);

// Check for errors and log if there is an issue with the query
if ($stmtWatering->errorCode() != '00000') {
    echo json_encode(['error' => 'Error fetching watering schedule']);
    exit();
}

$watering_history = $stmtWatering->fetch(PDO::FETCH_ASSOC);

// Query for lighting schedule
$queryLighting = "SELECT l.device_id, l.schedule_start, l.schedule_end 
                  FROM pencahayaan l
                  INNER JOIN device d ON l.device_id = d.device_id
                  WHERE d.user_id = :user_id
                  ORDER BY l.schedule_start DESC LIMIT 1";

$stmtLighting = $pdo->prepare($queryLighting);
$stmtLighting->execute(['user_id' => $user_id]);

if ($stmtLighting->errorCode() != '00000') {
    echo json_encode(['error' => 'Error fetching lighting schedule']);
    exit();
}

$lighting_history = $stmtLighting->fetch(PDO::FETCH_ASSOC);

// Query for fertilization schedule
$queryFertilization = "SELECT f.device_id, f.schedule_date 
                       FROM pemupukan f
                       INNER JOIN device d ON f.device_id = d.device_id
                       WHERE d.user_id = :user_id
                       ORDER BY f.schedule_date DESC LIMIT 1";

$stmtFertilization = $pdo->prepare($queryFertilization);
$stmtFertilization->execute(['user_id' => $user_id]);

if ($stmtFertilization->errorCode() != '00000') {
    echo json_encode(['error' => 'Error fetching fertilization schedule']);
    exit();
}

$fertilization_history = $stmtFertilization->fetch(PDO::FETCH_ASSOC);

// Get today's date
$current_date = date('Y-m-d');

// Format schedule data
$watering_schedule_start = isset($watering_history['schedule_start']) ? $current_date . ' ' . $watering_history['schedule_start'] : null;
$watering_schedule_end = isset($watering_history['schedule_end']) ? $current_date . ' ' . $watering_history['schedule_end'] : null;

$lighting_schedule_start = isset($lighting_history['schedule_start']) ? $current_date . ' ' . $lighting_history['schedule_start'] : null;
$lighting_schedule_end = isset($lighting_history['schedule_end']) ? $current_date . ' ' . $lighting_history['schedule_end'] : null;

$fertilization_schedule_date = isset($fertilization_history['schedule_date']) ? $fertilization_history['schedule_date'] : null;

// Combine data into a single array for JSON response
$data = [
    'watering' => [
        'schedule_start' => $watering_schedule_start,
        'schedule_end' => $watering_schedule_end
    ],
    'lighting' => [
        'schedule_start' => $lighting_schedule_start,
        'schedule_end' => $lighting_schedule_end
    ],
    'fertilization' => [
        'schedule_date' => $fertilization_schedule_date
    ]
];

// Send JSON response
try {
    // Clear any existing output
    if (ob_get_length()) {
        ob_clean();
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to send JSON response', 'details' => $e->getMessage()]);
}
exit();
?>
