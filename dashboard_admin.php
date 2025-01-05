    <?php
session_start();
require 'db_connection.php';

$total_user = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
$total_device = $pdo->query("SELECT COUNT(*) FROM device")->fetchColumn();

// Ambil daftar konsumen dari database
$query_konsumen = $pdo->query("SELECT username, user_id FROM user");
$konsumen = $query_konsumen->fetchAll(PDO::FETCH_ASSOC);


// Ambil daftar produk (device) dari database
$query_produk = $pdo->query("SELECT device_name, user_id FROM device");
$produk = $query_produk->fetchAll(PDO::FETCH_ASSOC);


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
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">
    <!-- Sidebar -->
    <div class="flex min-h-screen ">
        <aside class="w-64 bg-green-700 text-white flex-shrink-0">
            <div class="p-4 text-center font-bold text-xl">Admin Panel</div>
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li><a href="dashboard_admin.php" class="block px-4 py-2 hover:bg-green-600">Dashboard</a></li>
                    <li><a href="register.php" class="block px-4 py-2 hover:bg-green-600">Daftar User</a></li>
                    <li><a href="device.php" class="block px-4 py-2 hover:bg-green-600">daftar Device</a></li>
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
                    <h2 class="text-xl font-bold mb-4">Dashboard</h2>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Kotak Total User -->
                    <div class="bg-green-100 p-4 rounded-lg shadow-md text-center">
                        <p class="text-2xl font-bold"><?php echo $total_user; ?></p>
                        <p class="text-gray-600">Total User</p>
                    </div>
                    
                    <!-- Kotak Total Device -->
                    <div class="bg-green-100 p-4 rounded-lg shadow-md text-center">
                        <p class="text-2xl font-bold"><?php echo $total_device; ?></p>
                        <p class="text-gray-600">Total Device</p>
                    </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Daftar Konsumen -->
                        <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                            <h3 class="font-bold mb-2">Daftar Konsumen</h3>
                            <ul class="space-y-2">
                                <?php foreach ($konsumen as $k): ?>
                                    <li class="flex justify-between items-center">
                                        <span><?= htmlspecialchars($k['username']) ?></span>
                                        <button class="bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600"
                                            onclick="showUserId('<?= $k['user_id'] ?>')">
                                            Detail
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                            <!-- Daftar Device -->
                            <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                            <h3 class="font-bold mb-2">Daftar Device</h3>
                            <ul class="space-y-2">
                                <?php foreach ($produk as $p): ?>
                                    <li class="flex justify-between items-center">
                                        <span><?= htmlspecialchars($p['device_name']) ?></span>
                                        <button class="bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600"
                                            onclick="showUserId('<?= $p['user_id'] ?>')">
                                            Detail
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>


                        <!-- Popup Modal -->
                        <div id="userIdPopup" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                            <div class="bg-white p-6 rounded-lg shadow-md w-64">
                                <h4 class="text-xl font-bold mb-4">User ID</h4>
                                <p id="userIdDisplay" class="text-gray-700 text-lg"></p>
                                <button class="bg-red-500 text-white px-4 py-2 mt-4 rounded hover:bg-red-600"
                                    onclick="closePopup()">Close</button>
                            </div>
                        </div>


                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
    // Function to show the user ID in the popup
    function showUserId(userId) {
        document.getElementById('userIdDisplay').textContent = userId;
        document.getElementById('userIdPopup').classList.remove('hidden');
    }

    // Function to close the popup
    function closePopup() {
        document.getElementById('userIdPopup').classList.add('hidden');
    }
</script>
</body>

</html>