<?php
session_start();
require 'db_connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user = new User($pdo);
$users = $user->getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Media Perawatan Bibit Kakao</title>
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
                    <h2 class="text-xl font-bold mb-4">Register User</h2>
                    <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-4" type="button">
                        Tambah User
                    </button>


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $index => $data) : ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <?= $index + 1 ?>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?= $data['username'] ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?= $data['email'] ?>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button class="font-medium text-blue-600 dark:text-blue-500 hover:underline" data-modal-target="edit-modal-<?= $data['user_id'] ?>" data-modal-toggle="edit-modal-<?= $data['user_id'] ?>">Edit</button>
                                            <button class="font-medium text-red-600 dark:text-red-500 hover:underline" data-modal-target="delete-modal-<?= $data['user_id'] ?>" data-modal-toggle="delete-modal-<?= $data['user_id'] ?>">Delete</button>
                                            <button class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline" onclick="showResetPasswordModal(<?= $data['user_id'] ?>)">Reset Password</button>
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
                    <form method="POST" action="user.php">
                        <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                        <input type="text" name="username" id="username" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                        <input type="password" name="pass" id="password" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <button type="submit" name="action" value="create" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                            Register
                        </button>
                    </form>
                </div>
                <!-- Popup Notification -->
                <div id="success-popup" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded shadow-lg text-center">
                        <p class="text-lg font-semibold">User registered successfully!</p>
                        <button id="close-popup" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal -->
    <?php foreach ($users as $index => $data) : ?>
        <div id="edit-modal-<?= $data['user_id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Edit User
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " data-modal-hide="edit-modal-<?= $data['user_id'] ?>">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form method="POST" action="user.php">
                            <input type="text" name="user_id" value="<?= $data['user_id'] ?>" class="hidden">
                            <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                            <input type="text" name="username" id="username" value="<?= $data['username'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

                            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                            <input type="email" name="email" id="email" value="<?= $data['email'] ?>" class="w-full p-3 border border-gray-300 rounded mb-4" required>

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
    <?php foreach ($users as $index => $data) : ?>
        <div id="delete-modal-<?= $data['user_id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Delete User
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " data-modal-hide="delete-modal-<?= $data['user_id'] ?>">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form method="POST" action="user.php">
                            <input type="text" name="user_id" value="<?= $data['user_id'] ?>" class="hidden">
                            <p class="text-gray-700 mb-4 text-lg">Are you sure you want to delete this user <span class="font-bold"><?= $data['username'] ?></span>?</p>
                            <button type="submit" name="action" value="delete" class="w-full bg-red-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                                delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="reset-password-modal-<?= $data['user_id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 bottom-0 left-0 z-50 justify-center items-center flex w-full h-full">
    <div class="relative p-4 w-full max-w-md max-h-full bg-white rounded-lg shadow">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Reset Password
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto " onclick="closeModal(<?= $data['user_id'] ?>)">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form method="POST" action="reset_pass_proses.php">
                    <input type="text" name="user_id" value="<?= $data['user_id'] ?>" class="hidden">
                    <p class="text-gray-700 mb-4 text-lg">Apakah Anda yakin ingin mereset password untuk pengguna <span class="font-bold"><?= $data['username'] ?></span>?</p>
                    <button type="submit" name="action" value="reset" class="w-full bg-blue-600 text-white py-3 rounded font-bold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


        
    <?php endforeach ?>
    <script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman

        // Ambil data dari form
        const formData = new FormData(this);

        // Kirim data ke backend dengan fetch
        fetch('user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === "User registered successfully!") {
                // Tampilkan popup
                document.getElementById('success-popup').classList.remove('hidden');
            } else {
                alert("Error: " + result);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function resetPassword(userId) {
    console.log("Fungsi resetPassword dipanggil dengan userId:", userId);

    fetch('reset_pass_proses.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.text())
    .then(result => {
        console.log("Hasil dari backend:", result);
        if (result.trim() === "Password reset successful!") {
            alert("Password berhasil direset ke '123'.");
        } else {
            alert("Terjadi kesalahan: " + result);
        }
    })
    .catch(error => console.error("Terjadi kesalahan:", error));
}




    // Tutup popup
    document.getElementById('close-popup').addEventListener('click', function() {
        document.getElementById('success-popup').classList.add('hidden');
    });

    function showResetPasswordModal(userId) {
        // Menampilkan modal dengan ID yang sesuai
        const modal = document.getElementById("reset-password-modal-" + userId);
        if (modal) {
            modal.classList.remove('hidden'); // Menampilkan modal dengan menghapus kelas 'hidden'
        }
    }

    // Fungsi untuk menutup modal
    function closeModal(userId) {
        // Menyembunyikan modal dengan ID yang sesuai
        const modal = document.getElementById("reset-password-modal-" + userId);
        if (modal) {
            modal.classList.add('hidden'); // Menyembunyikan modal dengan menambahkan kelas 'hidden'
        }
    }
</script>


</body>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

</html>