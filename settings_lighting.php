<?php
if (isset($_GET['error'])) {
    echo "<div style='color: red;'>" . htmlspecialchars($_GET['error']) . "</div>";
}
if (isset($_GET['success'])) {
    echo "<div style='color: green;'>" . htmlspecialchars($_GET['success']) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Pencahayaan - Sistem Perawatan Bibit Kakao</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<nav class="bg-green-700 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="img/foto_logo.png" alt="Logo Kakao" class="h-10 w-10 rounded-full">
                <a href="dashboard.php" class="text-white text-2xl font-bold">Perawatan Bibit Kakao</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white hover:text-green-300 font-bold">Dashboard</a>
                <a href="about.php" class="text-white hover:text-green-300 font-bold">About Us</a>
                <a href="history.php" class="text-white hover:text-green-300 font-bold">History</a>
                
                <!-- Dropdown dengan Foto Profil -->
                <div class="relative">
                    <img src="img/rivaldo.jpg" alt="Profil" class="h-10 w-10 rounded-full cursor-pointer" id="profilePhoto">
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 hidden" id="profileMenu">
                        <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil Anda</a>
                        <a href="edit_profil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Pengaturan Profil</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-red-500 hover:text-white">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>

        const profilePhoto = document.getElementById('profilePhoto');
        const profileMenu = document.getElementById('profileMenu');
        
        profilePhoto.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', (event) => {
            if (!profilePhoto.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
    <div class="container mx-auto py-12 px-4">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Pengaturan Pencahayaan</h2>

        <!-- Status -->
        <?php if (isset($status)) : ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6 text-center font-semibold">
                <?php echo $status; ?>
            </div>
        <?php endif; ?>

        <!-- Tombol On/Off -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-8 text-center">
            <h3 class="text-2xl font-bold mb-4 text-green-600">Kontrol Manual</h3>
            <form action="proses_pencahayaan.php" method="POST">
                <button type="submit" name="manual_control" value="1" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">On</button>
                <button type="submit" name="manual_control" value="2" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Off</button>
            </form>
        </div>

        <!-- Pengaturan Jadwal dengan Waktu Mulai dan Selesai -->
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <h3 class="text-2xl font-bold mb-4 text-green-600">Pengaturan Jadwal</h3>
            <p class="text-gray-600 mb-4">Atur waktu mulai dan selesai untuk pencahayaan otomatis.</p>
            <form method="POST" action="proses_pencahayaan.php">
                <label for="schedule_start">Schedule On (Jam):</label>
                <input type="time" name="schedule_start" id="schedule_start" required><br>

                <label for="schedule_end">Schedule Off (Jam):</label>
                <input type="time" name="schedule_end" id="schedule_end" required><br>

                <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-full mr-2 hover:bg-green-700">Simpan</button>
            </form>


        </div>
    </div>
</body>
</html>
