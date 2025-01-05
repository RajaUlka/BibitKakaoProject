<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-green-600 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <button class="mr-4 focus:outline-none">
                <!-- Hamburger Icon -->
                <div class="space-y-1">
                    <div class="w-6 h-1 bg-white"></div>
                    <div class="w-6 h-1 bg-white"></div>
                    <div class="w-6 h-1 bg-white"></div>
                </div>
            </button>
            <span class="text-lg font-semibold">Dashboard</span>
        </div>
        <div class="flex space-x-4">
            <button class="bg-green-700 px-4 py-2 rounded hover:bg-green-800">Admin</button>
            <button class="bg-red-700 px-4 py-2 rounded hover:bg-red-800">Keluar</button>
        </div>
    </nav>

    <!-- Content -->
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-4">Profil Admin</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Section -->
            <div class="bg-white shadow-md rounded p-6">
                <div class="flex flex-col items-center mb-4">
                    <!-- Profile Picture -->
                    <div class="w-24 h-24 bg-gray-300 rounded-full mb-4"></div>
                    <h2 class="text-lg font-semibold">Nama Admin</h2>
                    <p class="text-gray-500">admin@example.com</p>
                </div>
                <!-- List Items -->
                <ul class="space-y-4">
                    <li class="flex items-center justify-between bg-gray-100 p-4 rounded">
                        <span>Item 1</span>
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Aksi</button>
                    </li>
                    <li class="flex items-center justify-between bg-gray-100 p-4 rounded">
                        <span>Item 2</span>
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Aksi</button>
                    </li>
                    <li class="flex items-center justify-between bg-gray-100 p-4 rounded">
                        <span>Item 3</span>
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Aksi</button>
                    </li>
                </ul>
            </div>

            <!-- Right Section -->
            <div class="bg-white shadow-md rounded p-6">
                <div class="space-y-4">
                    <!-- Form Group 1 -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Field 1</label>
                        <input type="text" class="w-full border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-green-300">
                    </div>
                    <!-- Form Group 2 -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Field 2</label>
                        <input type="text" class="w-full border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-green-300">
                    </div>
                    <!-- Form Group 3 -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Field 3</label>
                        <input type="text" class="w-full border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-green-300">
                    </div>
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
