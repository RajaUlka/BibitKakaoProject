<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
<nav class="bg-green-700 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <img src="img/foto_logo.png" alt="Logo Kakao" class="h-10 w-10 rounded-full">
            <a href="dashboard.php" class="text-white text-2xl font-bold">Perawatan Bibit Kakao</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="dashboard.php" class="text-white hover:text-green-300 font-bold">Dashboard</a>
            <a href="about.php" class="text-white hover:text-green-300 font-bold">About Us</a>
            <div class="relative">
                <img src="img/foto_logo.png" alt="Profil" class="h-10 w-10 rounded-full cursor-pointer" id="profilePhoto">
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 hidden" id="profileMenu">
                    <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil Anda</a>
                    <a href="settings_profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Pengaturan Profil</a>
                    <a href=".php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"></a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-red-500 hover:text-white">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Script untuk menampilkan dan menyembunyikan dropdown saat foto profil diklik
    const profilePhoto = document.getElementById('profilePhoto');
    const profileMenu = document.getElementById('profileMenu');
    
    profilePhoto.addEventListener('click', () => {
        profileMenu.classList.toggle('hidden');
    });
</script>


<script>
    // Script untuk menampilkan dan menyembunyikan dropdown saat tombol 'Profil' diklik
    const dropdownButton = document.querySelector('button');
    const dropdownMenu = document.querySelector('div.absolute');
    
    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });
</script>

    <!-- About Us Section -->
    <section class="container mx-auto py-12 px-4">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">About Us</h2>
        <div class="text-lg text-gray-700 max-w-3xl mx-auto">
            <p class="mb-4">
                Sistem Perawatan Bibit Kakao ini dirancang untuk mempermudah petani dalam mengelola penyiraman, pencahayaan, dan pemberian pupuk pada bibit kakao secara otomatis menggunakan teknologi IoT. Sistem ini memungkinkan pemantauan kondisi tanaman secara real-time dan dapat diakses dari mana saja.
            </p>
            <p class="mb-4">
                Proyek ini dikembangkan oleh tim IF-36, yang dipimpin oleh Rivaldo A Situmorang dengan bimbingan dari Andy Trywinarto ST., MT., Ph.D. Tujuan dari proyek ini adalah untuk meningkatkan efisiensi dan efektivitas dalam perawatan bibit kakao.
            </p>
            <p>
                Kami berkomitmen untuk terus meningkatkan sistem ini agar semakin bermanfaat bagi petani kakao dan komunitas agrikultur.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-700 text-white py-6">
        <div class="container mx-auto text-center">
            <p class="text-lg font-semibold">Sistem Perawatan Bibit Kakao</p>
            <p class="text-sm">Dikembangkan oleh Tim Admin: Erwandi Maulana</p>
            <p class="text-sm">Hak Cipta &copy; <?php echo date("Y"); ?> - Semua Hak Dilindungi</p>
        </div>
    </footer>
</body>
</html>
