<?php
session_start();
require 'db_connection.php'; // Koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    die("Harap login terlebih dahulu.");
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Mengambil device_id yang terkait dengan user_id yang sedang login
$stmtDevice = $pdo->prepare("SELECT device_id FROM device WHERE user_id = :user_id");
$stmtDevice->execute(['user_id' => $user_id]);
$devices = $stmtDevice->fetchAll(PDO::FETCH_ASSOC);

// Ambil device_id dari perangkat yang dimiliki oleh user
$device_ids = array_column($devices, 'device_id');

// Jika tidak ada perangkat yang dimiliki oleh pengguna, hentikan eksekusi
if (empty($device_ids)) {
    $wateringHistory = [];
    $fertilizationHistory = [];
    $lightingHistory = [];
} else {
    // Query Riwayat Penyiraman
    $deviceIdPlaceholder = implode(',', array_fill(0, count($device_ids), '?'));
    $stmtWateringHistory = $pdo->prepare("SELECT device_id, min_humidity, max_humidity, schedule_start, action_timestamp 
        FROM histori_penyiraman WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtWateringHistory->execute($device_ids);
    $wateringHistory = $stmtWateringHistory->fetchAll(PDO::FETCH_ASSOC);

    // Query Riwayat Pemupukan
    $stmtFertilizationHistory = $pdo->prepare("SELECT device_id, schedule_date, action_timestamp 
        FROM histori_pemupukan WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtFertilizationHistory->execute($device_ids);
    $fertilizationHistory = $stmtFertilizationHistory->fetchAll(PDO::FETCH_ASSOC);

    // Query Riwayat Pencahayaan
    $stmtLightingHistory = $pdo->prepare("SELECT device_id, manual_control, schedule_start, schedule_end, action_timestamp 
        FROM histori_pencahayaan WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtLightingHistory->execute($device_ids);
    $lightingHistory = $stmtLightingHistory->fetchAll(PDO::FETCH_ASSOC);
}



// Mengambil riwayat dari masing-masing tabel
if (!empty($device_ids)) {
    // Buat placeholder hanya jika ada device_id
    $deviceIdPlaceholder = implode(',', array_fill(0, count($device_ids), '?'));

    // Query Riwayat Penyiraman
    $stmtWateringHistory = $pdo->prepare("SELECT device_id, min_humidity, max_humidity, schedule_start, action_timestamp 
        FROM histori_penyiraman WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtWateringHistory->execute($device_ids);
    $wateringHistory = $stmtWateringHistory->fetchAll(PDO::FETCH_ASSOC);

    // Query Riwayat Pemupukan
    $stmtFertilizationHistory = $pdo->prepare("SELECT device_id, schedule_date, action_timestamp 
        FROM histori_pemupukan WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtFertilizationHistory->execute($device_ids);
    $fertilizationHistory = $stmtFertilizationHistory->fetchAll(PDO::FETCH_ASSOC);

    // Query Riwayat Pencahayaan
    $stmtLightingHistory = $pdo->prepare("SELECT device_id, manual_control, schedule_start, schedule_end, action_timestamp 
        FROM histori_pencahayaan WHERE device_id IN ($deviceIdPlaceholder) ORDER BY action_timestamp DESC LIMIT 10");
    $stmtLightingHistory->execute($device_ids);
    $lightingHistory = $stmtLightingHistory->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Kosongkan hasil jika tidak ada device_id
    $wateringHistory = [];
    $fertilizationHistory = [];
    $lightingHistory = [];
}


// Fungsi untuk mengubah hari ke Bahasa Indonesia
function translateDayToIndonesian($day) {
    $days = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];
    return $days[$day] ?? $day;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Perawatan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profilePhoto = document.getElementById('profilePhoto');
            const profileMenu = document.getElementById('profileMenu');

            profilePhoto.addEventListener('click', () => {
                profileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profilePhoto.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-green-700 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="img/foto_logo.png" alt="Logo Kakao" class="h-10 w-10 rounded-full">
                <a href="dashboard.php" class="text-white text-2xl font-bold">Perawatan Bibit Kakao</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white hover:text-green-300 font-bold">Dashboard</a>
                <a href="about.php" class="text-white hover:text-green-300 font-bold">About Us</a>
                <a href="history.php" class="text-white hover:text-green-300 font-bold">History</a>
                <div class="relative">
                    <img src="img/foto_logo.png" alt="Profil" class="h-10 w-10 rounded-full cursor-pointer" id="profilePhoto">
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 hidden" id="profileMenu">
                        <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil Anda</a>
                        <a href="edit_profil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Pengaturan Profil</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-red-500 hover:text-white">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="container mx-auto my-8">
        <h1 class="text-3xl font-bold text-green-700 mb-4">Riwayat Perawatan</h1>
        <div class="mb-4">
            <a href="dashboard.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-500">Kembali ke Dashboard</a>
        </div>

        <!-- Riwayat Penyiraman -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Riwayat Penyiraman</h2>
        <table class="table-auto w-full bg-white shadow-md rounded mb-4">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-2">Device ID</th>
                    <th class="px-4 py-2">Jadwal Penyiraman</th>
                    <th class="px-4 py-2">Tahun</th>
                    <th class="px-4 py-2">Jam</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Tanggal Pelaksanaan</th>
                    <th class="px-4 py-2">Kelembapan Minimum</th>
                    <th class="px-4 py-2">Kelembapan Maksimum</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($wateringHistory)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-gray-500">Belum ada riwayat penyiraman.</td>
                    </tr>
                <?php else: ?>
                <?php foreach ($wateringHistory as $row): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $row['device_id']; ?></td>
                    <td class="px-4 py-2"><?= $row['schedule_start']; ?></td>
                    <td class="px-4 py-2"><?= date("Y", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= date("H:i", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= translateDayToIndonesian(date("l", strtotime($row['action_timestamp']))); ?></td>
                    <td class="px-4 py-2"><?= date("d-m-Y", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= $row['min_humidity']; ?></td>
                    <td class="px-4 py-2"><?= $row['max_humidity']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Riwayat Pemupukan -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Riwayat Pemupukan</h2>
        <table class="table-auto w-full bg-white shadow-md rounded mb-4">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-2">Device ID</th>
                    <th class="px-4 py-2">Jadwal Pemupukan</th>
                    <th class="px-4 py-2">Tahun</th>
                    <th class="px-4 py-2">Jam</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Tanggal Pelaksanaan</th>
                </tr>
            </thead>
            <tbody>
                 <?php if (empty($fertilizationHistory)): ?>
                <tr>
                    <td colspan="6" class="text-center text-gray-500">Belum ada riwayat pemupukan.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($fertilizationHistory as $row): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $row['device_id']; ?></td>
                    <td class="px-4 py-2"><?= $row['schedule_date']; ?></td>
                    <td class="px-4 py-2"><?= date("Y", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= date("H:i", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= translateDayToIndonesian(date("l", strtotime($row['action_timestamp']))); ?></td>
                    <td class="px-4 py-2"><?= date("d-m-Y", strtotime($row['action_timestamp'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Riwayat Pencahayaan -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Riwayat Pencahayaan</h2>
        <table class="table-auto w-full bg-white shadow-md rounded">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-2">Device ID</th>
                    <th class="px-4 py-2">Kontrol Manual</th>
                    <th class="px-4 py-2">Jadwal Pencahayaan</th>
                    <th class="px-4 py-2">Tahun</th>
                    <th class="px-4 py-2">Jam</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Tanggal Pelaksanaan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lightingHistory)): ?>
                <tr>
                    <td colspan="7" class="text-center text-gray-500">Belum ada riwayat pencahayaan.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($lightingHistory as $row): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $row['device_id']; ?></td>
                    <td class="px-4 py-2"><?= $row['manual_control'] == 0 ? "Otomatis" : ($row['manual_control'] == 1 ? "On" : ($row['manual_control'] == 2 ? "Off" : "Tidak Diketahui")); ?></td>
                    <td class="px-4 py-2"><?= $row['schedule_start'] . ' - ' . $row['schedule_end']; ?></td>
                    <td class="px-4 py-2"><?= date("Y", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= date("H:i", strtotime($row['action_timestamp'])); ?></td>
                    <td class="px-4 py-2"><?= translateDayToIndonesian(date("l", strtotime($row['action_timestamp']))); ?></td>
                    <td class="px-4 py-2"><?= date("d-m-Y", strtotime($row['action_timestamp'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

