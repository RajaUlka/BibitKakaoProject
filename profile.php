<?php
session_start();
require 'db_connection.php';

// Cek apakah user sudah login, jika belum, redirect ke login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

// Query untuk mengambil data pengguna berdasarkan user_id dari tabel 'user'
$query = "SELECT username, email, created_at FROM user WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data tidak ditemukan
if (!$user) {
    echo "Data pengguna tidak ditemukan.";
    exit();
}

// Ambil data
$username = $user['username'];
$email = $user['email'];
$created_at = $user['created_at'];

// Format tanggal bergabung (ambil tahun-bulan-tanggal tanpa jam)
$joined_date = date('Y-m-d', strtotime($created_at));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
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
                    <!-- Foto profil pengguna -->
                    <img src="img/foto_logo.png" alt="Profil" class="h-10 w-10 rounded-full cursor-pointer" id="profilePhoto">
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 hidden" id="profileMenu">
                        <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil Anda</a>
                        <a href="edit_profil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Pengaturan Profil</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-red-500 hover:text-white">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Script untuk menampilkan dan menyembunyikan dropdown saat foto profil diklik
            const profilePhoto = document.getElementById('profilePhoto');
            const profileMenu = document.getElementById('profileMenu');
            profilePhoto.addEventListener('click', () => {
                profileMenu.classList.toggle('hidden');
            });
        </script>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-green-700 text-white transform -translate-x-full transition-transform duration-300 z-40">
        <div class="p-4">
            <h2 class="text-xl font-bold mb-4">Menu Sistem</h2>
            <ul class="space-y-4">
                <li>
                    <a href="settings_watering.php" class="block bg-green-600 hover:bg-green-500 text-center text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition duration-200">Penyiraman</a>
                </li>
                <li>
                    <a href="settings_lighting.php" class="block bg-yellow-600 hover:bg-yellow-500 text-center text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition duration-200">Pencahayaan</a>
                </li>
                <li>
                    <a href="settings_fertilization.php" class="block bg-blue-600 hover:bg-blue-500 text-center text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition duration-200">Pemberian Pupuk</a>
                </li>
                <li>
                    <a href="https://wa.me/083852743444" target="_blank" class="block bg-red-600 hover:bg-red-500 text-center text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition duration-200">Hubungi Admin</a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Content -->
    <section class="container mx-auto my-8 p-6 bg-white rounded-lg shadow-lg">
        <div class="flex flex-col items-center">
            <!-- Foto Profil -->
            <img class="w-32 h-32 rounded-full shadow-md" src="img/foto_logo.png" alt="Foto Profil">
            <!-- Informasi Pengguna -->
            <h2 class="mt-4 text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($username); ?></h2>
            <p class="text-gray-600"><?php echo htmlspecialchars($email); ?></p>
        </div>
    </section>

    <!-- Data Pengguna -->
    <div class="container mx-auto my-8 p-6 bg-gray-100 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Data Pengguna</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-md text-center">
                <h4 class="text-lg font-semibold text-gray-800">Username</h4>
                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($username); ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-center">
                <h4 class="text-lg font-semibold text-gray-800">Email</h4>
                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($email); ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-center">
                <h4 class="text-lg font-semibold text-gray-800">Bergabung Sejak</h4>
                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($joined_date); ?></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-green-700 text-white py-6 mt-8">
        <div class="container mx-auto text-center">
            <p class="text-lg font-semibold">Sistem Perawatan Bibit Kakao</p>
            <p class="text-sm">Dikembangkan oleh Tim Admin: Rivaldo</p>
            <p class="text-sm">Hak Cipta &copy; <?php echo date("Y"); ?> - Semua Hak Dilindungi</p>
        </div>
    </footer>

    <!-- Sidebar Toggle Script -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        // Toggle sidebar
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>
