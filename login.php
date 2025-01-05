<?php
session_start();
require 'db_connection.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pass = $_POST['pass'];

    
    
    $sql = "SELECT * FROM user WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['pass'])) {
        $_SESSION['user_id'] = $user['user_id'];
        
        
        error_log("Password input: " . $pass);
        error_log("Password hash dari database: " . $user['pass']);
        error_log("Password verify result: " . (password_verify($pass, $user['pass']) ? "success" : "failure"));
        
        if ($user['user_id'] == 2) {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "Username atau pass salah!";
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Media Perawatan Bibit Kakao</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto p-6">
        <div class="max-w-md mx-auto bg-white rounded p-8 shadow">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo $error; ?></p>
                
            <?php endif; ?>
            <form method="POST" action="login.php">
                <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                <input type="text" name="username" id="username" class="w-full p-3 border border-gray-300 rounded mb-4" required>
                
                <label for="pass" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" name="pass" id="pass" class="w-full p-3 border border-gray-300 rounded mb-4" required>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                    Login
                </button>
            </form>
            <p class="text-center mt-4">Lupa Password?? <a href="https://wa.me/083852743444" class="text-green-600 font-bold">Hubungin Admin</a></p>
        </div>
    </div>

</body>
</html>
