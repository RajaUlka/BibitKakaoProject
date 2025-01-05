<?php
session_start();
require 'db_connection.php';

$device = new Device($pdo);  // Membuat objek Device
$devices = $device->getAllDevices();  // Mendapatkan semua devices

$user = new User($pdo);  // Membuat objek User
$users = $user->getAllUsers();  // Mendapatkan semua users

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device = new Device($pdo);

    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $device_name = $_POST['device_name'];
        $device_type = $_POST['device_type'];
        $device_status = $_POST['device_status'];
        $user_id = $_POST['user_id'];
        $created_at = date('Y-m-d H:i:s'); // Timestamp untuk created_at
        $device->create($device_name, $device_type, $device_status, $user_id, $created_at);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $device_id = $_POST['device_id'];
        $device_name = $_POST['device_name'];
        $device_type = $_POST['device_type'];
        $device_status = $_POST['device_status'];
        $user_id = $_POST['user_id'];
        $device->update($device_id, $device_name, $device_type, $device_status, $user_id);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $device_id = $_POST['device_id'];
        $device->delete($device_id);
    }
}           
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device - Media Perawatan Bibit Kakao</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Sidebar -->
    <div class="flex min-h-screen ">
        <aside class="w-64 bg-green-700 text-white flex-shrink-0">
            <div class="p-4 text-center font-bold text-xl">Admin Panel</div>
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li><a href="dashboard_admin.php" class="block px-4 py-2 hover:bg-green-600">Dashboard</a></li>
                    <li><a href="register.php" class="block px-4 py-2 hover:bg-green-600">Daftar User</a></li>
                    <li><a href="device.php" class="block px-4 py-2 hover:bg-green-600">Daftar Device</a></li>
                    <!-- <li><a href="#" class="block px-4 py-2 hover:bg-green-600">Data Konsumen</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-green-600">Data Produk</a></li> -->
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">    
            <!-- Header -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Dashboard <span class=" text-green-800 px-25 py-2 rounded">Admin</span></h1>
                <div class="flex space-x-4">
                    <a href="logout.php" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Keluar</a>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">

                <!-- Dashboard Admin -->
                <section class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Device</h2>
                    <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-4" type="button">
                        Tambah Device
                    </button>


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nama Device
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        ID Device
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($devices as $index => $data) : ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <?= $index + 1 ?>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?= $data['device_name'] ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?= $data['device_id'] ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?= $data['device_status'] ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button class="font-medium text-blue-600 dark:text-blue-500 hover:underline" data-modal-target="edit-modal-<?= $data['device_id'] ?>" data-modal-toggle="edit-modal-<?= $data['device_id'] ?>">Edit</button>
                                            <button class="font-medium text-red-600 dark:text-red-500 hover:underline" data-modal-target="delete-modal-<?= $data['device_id'] ?>" data-modal-toggle="delete-modal-<?= $data['device_id'] ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                </section>
            </main>
        </div>
    </div>

    <!-- Main modal -->
    <div id="tambah-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                    <h3 class="text-xl font-semibold text-gray-900 ">
                        Tambah User
                    </h3>
                    <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " data-modal-hide="tambah-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form method="POST" action="device_proses.php">
                        <label for="device_name" class="block text-gray-700 font-bold mb-2">Device Name</label>
                        <input type="text" name="device_name" id="device_name" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="device_type" class="block text-gray-700 font-bold mb-2">Device Type</label>
                        <input type="text" name="device_type" id="device_type" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="device_status" class="block text-gray-700 font-bold mb-2">Device Status</label>
                        <input type="text" name="device_status" id="device_status" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="user_id" class="block text-gray-700 font-bold mb-2">User ID</label>
                        <select name="user_id" id="user_id" class="w-full p-3 border border-gray-300 rounded mb-4">
                            <option value="" selected disabled>Select User</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['user_id'] ?>"><?= $user['username'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" name="action" value="create" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal -->
    <?php foreach ($devices as $index => $data) : ?>
        <div id="edit-modal-<?= $data['device_id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Edit Device
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " data-modal-hide="edit-modal-<?= $data['device_id'] ?>">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form method="POST" action="device_proses.php">
                            <input type="text" name="device_id" value="<?= $data['device_id'] ?>" class="hidden">
                            <label for="device_name" class="block text-gray-700 font-bold mb-2">Device Name</label>
                            <input type="text" name="device_name" id="device_name" value="<?= $data['device_name'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                            <label for="device_name" class="block text-gray-700 font-bold mb-2">Device Name</label>
                            <input type="text" name="device_name" id="device_name" value="<?= $data['device_name'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                            <label for="device_type" class="block text-gray-700 font-bold mb-2">Device Type</label>
                            <input type="text" name="device_type" id="device_type" value="<?= $data['device_type'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                            <label for="device_status" class="block text-gray-700 font-bold mb-2">Device Status</label>
                            <input type="text" name="device_status" id="device_status" value="<?= $data['device_status'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                            <label for="user_id" class="block text-gray-700 font-bold mb-2">User ID</label>
                            <select name="user_id" id="user_id" class="w-full p-3 border border-gray-300 rounded mb-4">
                                <option value="" selected disabled>Select User</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['user_id'] ?>" <?php if ($user['user_id'] == $data['user_id']) echo 'selected'; ?>><?= $user['username'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="action" value="update" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                                Edit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>

    <!-- Delete modal -->
    <?php foreach ($devices as $index => $data) : ?>
        <div id="delete-modal-<?= $data['device_id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Delete Device
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " data-modal-hide="delete-modal-<?= $data['device_id'] ?>">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form method="POST" action="device_proses.php">
                            <input type="text" name="device_id" value="<?= $data['device_id'] ?>" class="hidden">
                            <p class="text-gray-700 mb-4 text-lg">Are you sure you want to delete this device <span class="font-bold"><?= $data['device_name'] ?></span>?</p>
                            <button type="submit" name="action" value="delete" class="w-full bg-red-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                                delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

</html>