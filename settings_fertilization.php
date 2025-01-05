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
    <title>Pengaturan Pemberian Pupuk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<nav class="bg-green-700 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="img/foto_logo.png" alt="Logo Kakao" class="h-10 w-10 rounded-full">
                <a href="dashboard.php" class="text-white text-2xl font-bold">Perawatan Bibit Kakao</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white hover:text-green-300 font-bold">Dashboard</a>
                <a href="about.php" class="text-white hover:text-green-300 font-bold">About Us</a>
                <a href="history.php
                " class="text-white hover:text-green-300 font-bold">History</a>
                
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
    <div class="container mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold mb-6">Pengaturan Pemberian Pupuk</h2>

        <form action="proses_pemupukan.php" method="POST" class="bg-white p-6 rounded shadow-md mb-6">
            <label class="block text-gray-700 mb-2">Pilih Tanggal Pemberian Pupuk</label>
            <input type="date" name="fertilization_dates[]" multiple class="w-full p-2 border rounded mb-4" id="fertilization-dates">
            
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded mt-4">Simpan Jadwal</button>
        </form>
    </div>
    <!-- // atur tanggal pemupukan -->
    <script>
        const fertilizationDatesContainer = document.getElementById('fertilization-dates');

        function addDateInput() {
            const newDateInput = document.createElement('input');
            newDateInput.type = 'date';
            newDateInput.name = 'fertilization_dates[]';
            newDateInput.className = 'w-full p-2 border rounded mb-4';
            fertilizationDatesContainer.parentNode.insertBefore(newDateInput, fertilizationDatesContainer.nextSibling);
        }
        const addDateButton = document.createElement('button');
        addDateButton.textContent = 'Tambah Tanggal';
        addDateButton.type = 'button';
        addDateButton.className = 'bg-green-500 text-white py-2 px-4 rounded mb-4';
        addDateButton.onclick = addDateInput;
        fertilizationDatesContainer.parentNode.insertBefore(addDateButton, fertilizationDatesContainer.nextSibling);
    </script>
</body>
</html>
