<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Contact Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto p-6">
        <div class="max-w-md mx-auto bg-white rounded p-8 shadow">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Hubungi Admin</h2>

            <?php if (isset($success_message)): ?>
                <p class="text-green-500 mb-4 text-center"><?php echo $success_message; ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <form method="POST" action="dashboard.php">
                <label for="subject" class="block text-gray-700 font-bold mb-2">Subject</label>
                <input type="text" name="subject" id="subject" class="w-full p-3 border border-gray-300 rounded mb-4" required>
                
                <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
                <textarea name="message" id="message" rows="4" class="w-full p-3 border border-gray-300 rounded mb-4" required></textarea>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                    Send Message
                </button>
            </form>

            <p class="text-center mt-4">Kembali ke <a href="dashboard.php" class="text-green-600 font-bold">Dashboard</a></p>
        </div>
    </div>

</body>
</html>