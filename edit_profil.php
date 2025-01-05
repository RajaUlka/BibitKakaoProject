<?php
session_start();
require 'db_connection.php';  // Pastikan ini adalah file yang menghubungkan ke database

// Cek jika pengguna sudah login, jika tidak redirect ke login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Membuat instance dari Profil untuk mengambil data pengguna
$profil = new Profil($pdo);

// Ambil data pengguna berdasarkan user_id
$user = $profil->getUserById($user_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <!-- Navbar (header) -->
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
    </header>

    <!-- Container Utama -->
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-lg mx-auto mt-10">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">Edit Profil</h2>

        <div class="flex justify-center mb-4">
            <img src="img/foto_logo.png" alt="Logo Profil" class="w-24 h-24 rounded-full">
        </div>

        <div class="text-center mb-6">
            <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
            <p class="mt-1 block w-full text-gray-900 sm:text-sm"><?= htmlspecialchars($user['username']) ?></p>
        </div>

        <!-- Form -->
        <form action="proses_edit_profil.php" method="POST" class="space-y-4">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" autocomplete="email" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password (opsional):</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru (jika ingin diubah)" autocomplete="new-password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Hidden ID -->
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

            <!-- Action input untuk update -->
            <input type="hidden" name="action" value="update">

            <!-- Tombol Submit -->
            <div class="flex justify-center mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md shadow hover:bg-indigo-700">
                    Update Profil
                </button>
            </div>
        </form>

        <!-- Pesan Sukses -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="text-green-600 text-center mt-4"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Pesan Error -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="text-red-600 text-center mt-4"><?= htmlspecialchars($_SESSION['error_message']) ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <footer class="bg-green-700 text-white py-6 mt-8">
        <div class="container mx-auto text-center">
            <p class="text-lg font-semibold">Sistem Perawatan Bibit Kakao</p>
            <p class="text-sm">Dikembangkan oleh Tim Admin: Rivaldo</p>
            <p class="text-sm">Hak Cipta &copy; <?php echo date("Y"); ?> - Semua Hak Dilindungi</p>
        </div>
    </footer>

    <script>
        const profilePhoto = document.getElementById('profilePhoto');
        const profileMenu = document.getElementById('profileMenu');
        profilePhoto.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
