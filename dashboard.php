<?php
session_start();
require 'db_connection.php';

// Cek apakah user sudah login, jika belum, redirect ke login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Perawatan Bibit Kakao</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal">

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
    <!-- Hamburger Menu -->
    <button id="menuToggle" class="  top-4 left-4 z-50 bg-green-600 text-white p-2 rounded-md focus:outline-none shadow-md">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

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
                <li>
                <a href="https://wa.me/083852743444" target="_blank" class="block bg-red-600 hover:bg-red-500 text-center text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition duration-200">Hubungi Admin</a>
                </li>
            </ul>
        </div>
    </aside>

    <section class="container mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Status Tanaman</h2>
        <div id="realtime-status" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card Kelembapan Tanah -->
            <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                <h3 class="text-2xl font-bold mb-4 text-green-600">Kelembapan Tanah</h3>
                <p id="soil-humidity" class="text-3xl font-semibold text-gray-700">-</p>
            </div>
            <!-- Card Suhu -->
            <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                <h3 class="text-2xl font-bold mb-4 text-green-600">Suhu</h3>
                <p id="temperature" class="text-3xl font-semibold text-gray-700">-</p>
            </div>
            <!-- Card Cahaya -->
            <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                <h3 class="text-2xl font-bold mb-4 text-green-600">Cahaya</h3>
                <p id="light-intensity" class="text-3xl font-semibold text-gray-700">-</p>
            </div>
        </div>
        <p id="error-message" class="text-center text-red-600 mt-4 hidden">Gagal memuat daftar realtime</p>
    </section>


    <!-- Grid Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 py-6">
            <!-- Penyiraman -->
            <div class="bg-gray-200 p-6 rounded-lg text-center shadow-md hover:bg-gray-300 transition-colors">
                <p class="font-bold text-gray-700">Penyiraman</p>
                <p id="watering" class="text-gray-600 mt-2">Memuat jadwal...</p>
            </div>

            <!-- Pencahayaan -->
            <div class="bg-gray-200 p-6 rounded-lg text-center shadow-md hover:bg-gray-300 transition-colors">
                <p class="font-bold text-gray-700">Pencahayaan</p>
                <p id="lighting" class="text-gray-600 mt-2">Memuat jadwal...</p>
            </div>

            <!-- Pemberian Pupuk -->
            <div class="bg-gray-200 p-6 rounded-lg text-center shadow-md hover:bg-gray-300 transition-colors">
                <p class="font-bold text-gray-700">Pemberian Pupuk</p>
                <p id="fertilization" class="text-gray-600 mt-2">Memuat jadwal...</p>
            </div>

            <!-- Hubungi Admin -->
            <div class="bg-gray-200 p-6 rounded-lg text-center shadow-md hover:bg-gray-300 transition-colors">
                <p class="font-bold text-gray-700">Hubungi Admin</p>
                <p class="text-gray-600 mt-2">Jadwal: 24/7</p>
                <p class="text-gray-600 mt-2">
                    <a href="https://wa.me/083852743444" target="_blank" class="text-blue-600">Hubungi Admin via WhatsApp</a>
                </p>
            </div>
        </div>


    <!-- Tentang Kami -->
    <section class="container mx-auto py-12 px-4">
    <div class="bg-green-100 p-6 rounded-lg shadow-lg">
        <h2 class="text-4xl font-bold text-center text-green-800 mb-8">Tentang Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> <!-- Menyesuaikan gap -->
            <div class="flex justify-center">
                <img src="img/gmbf.jpg" alt="Tim Pengembang" class="rounded-lg shadow-md max-w-full">
            </div>
            <div class="text-lg text-gray-700">
                <p class="mb-4">
                    Sistem Perawatan Bibit Kakao ini dirancang untuk membantu petani mengelola bibit kakao menggunakan teknologi IoT. Dengan fitur pemantauan real-time dan pengaturan otomatis, kami berharap dapat meningkatkan produktivitas dan efisiensi petani.
                </p>
                <p class="mb-4">
                    Dikembangkan oleh tim IF-36 yang dipimpin oleh <strong>Rivaldo A Situmorang</strong> dengan bimbingan dari <strong>Andy Trywinarto, ST., MT., Ph.D</strong>.
                </p>
                <p>
                    Kami berkomitmen untuk terus berinovasi dalam membantu petani mencapai hasil yang lebih baik melalui teknologi.
                </p>
            </div>
        </div>
    </div>
</section>


    <!-- Footer -->
    <footer class="bg-green-700 text-white py-6">
        <div class="container mx-auto text-center">
            <p class="text-lg font-semibold">Sistem Perawatan Bibit Kakao</p>
            <p class="text-sm">Dikembangkan oleh Tim Admin: Rivaldo</p>
            <p class="text-sm">Hak Cipta &copy; <?php echo date("Y"); ?> - Semua Hak Dilindungi</p>
        </div>
    </footer>

    <!-- JavaScript -->
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

        // AJAX untuk mengambil data realtime
        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_realtime_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById("error-message").classList.remove("hidden");
                    } else {
                        // Update elemen dengan data dari server
                        document.getElementById("soil-humidity").textContent = data.soil_humidity + '%';
                        document.getElementById("temperature").textContent = data.temperature + '°C';
                        document.getElementById("light-intensity").textContent = data.light_intensity + ' candela';
                    }
                })
                .catch(error => {
                    document.getElementById("error-message").classList.remove("hidden");
                });
        });

                fetch('jadwal_user.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response Data:', data);

                if (data.error) {
                    console.error('Error:', data.error);
                    document.getElementById('watering').textContent = "Tidak ada jadwal.";
                    document.getElementById('lighting').textContent = "Tidak ada jadwal.";
                    document.getElementById('fertilization').textContent = "Tidak ada jadwal.";
                } else {
                    // Penyiraman
                    const wateringStart = data.watering.schedule_start ? new Date(data.watering.schedule_start) : null;
                    const wateringEnd = data.watering.schedule_end ? new Date(data.watering.schedule_end) : null;
                    const wateringSchedule = `
                        Jadwal berikutnya:
                        <br> Jam On: ${wateringStart ? wateringStart.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
                        <br> Jam Off: ${wateringEnd ? wateringEnd.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
                    `;
                    document.getElementById('watering').innerHTML = wateringSchedule;

                    // Pencahayaan
                    const lightingStart = data.lighting.schedule_start ? new Date(data.lighting.schedule_start) : null;
                    const lightingEnd = data.lighting.schedule_end ? new Date(data.lighting.schedule_end) : null;
                    const lightingSchedule = `
                        Jadwal berikutnya:
                        <br> Jam On: ${lightingStart ? lightingStart.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
                        <br> Jam Off: ${lightingEnd ? lightingEnd.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
                    `;
                    document.getElementById('lighting').innerHTML = lightingSchedule;

                    // Pemberian Pupuk
                    const fertilizationDate = data.fertilization.schedule_date ? new Date(data.fertilization.schedule_date) : null;
                    const fertilizationSchedule = `
                        Jadwal berikutnya:
                        <br> ${fertilizationDate ? fertilizationDate.toLocaleDateString() : 'N/A'}
                    `;
                    document.getElementById('fertilization').innerHTML = fertilizationSchedule;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('watering').textContent = "Gagal memuat jadwal.";
                document.getElementById('lighting').textContent = "Gagal memuat jadwal.";
                document.getElementById('fertilization').textContent = "Gagal memuat jadwal.";
            });


    </script>
</body>

</html>