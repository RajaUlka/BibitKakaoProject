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


    <!-- Modal toggle -->
    <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
        type="button">
        Toggle modal
    </button>

    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                    <h3 class="text-xl font-semibold text-gray-900 ">
                        Sign in to our platform
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto "
                        data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form method="POST" action="register.php" enctype="multipart/form-data">
                        <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                        <input type="text" name="username" id="username"
                            class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full p-3 border border-gray-300 rounded mb-4" required>

                        <label for="photo" class="block text-gray-700 font-bold mb-2">Profile Photo</label>
                        <input type="file" name="photo" id="photo"
                            class="w-full p-3 border border-gray-300 rounded mb-4" accept="image/*" required>

                        <label for="photo" class="block text-gray-700 font-bold mb-2">Profile Photo</label>
                        <select name="level" id="level"
                            class="mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 ">
                            <option value="">Pilih</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <div class="max-w-md mx-auto bg-white rounded p-8 shadow">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Register</h2>

            <p class="text-red-500 mb-4 text-center"></p>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>