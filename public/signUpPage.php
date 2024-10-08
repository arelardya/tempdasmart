    <?php
    // Koneksi ke functions.php
    require_once '../constant/functions.php';

    $registrationSuccess = false;
    $registrationError = '';

    // jika tombol registrasi sudah ditekan
    if (isset($_POST["register"])){
        if (regisAdmins($_POST) > 0){
            $registrationSuccess = true;
        } else{
            $registrationError = "Registration failed. Please try again.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="script.js"></script>
    </head>
    <body class="font-[Poppins] min-h-screen" style="background-image: url(./assets/bg_signIn.jpg);">

        <nav class="flex justify-between items-center bg-green-800 bg-opacity-70">
            <div>
                <a href="./index.html">
                    <img class="w-20 h-20" src="./assets/logo.png" alt="">
                </a>
            </div>
            <div>
                <ul class="flex items-center pr-5">
                    <li>
                        <a class="px-5 pb-3 text-white hover:underline" href="./market.html">Marketplace</a>
                    </li>
                    <li>
                        <a class="px-5 pb-3 text-white hover:underline" href="./aboutus.html">About Us</a>
                    </li>
                    <li>
                        <a class="p-3 px-4 text-green-800 bg-white rounded-3xl hover:bg-green-600" href="./signInPage.php">Sign In</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="flex justify-center items-center mt-20">
            <div class="bg-gray-300 bg-opacity-50 rounded-lg shadow-md p-10 w-9/12">
                <h2 class="text-2xl font-bold mb-4 text-center text-white">Sign In</h2>
                <?php if ($registrationSuccess): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">Registration completed successfully.</span>
                    </div>
                <?php endif; ?>
                <?php if ($registrationError): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline"><?php echo $registrationError; ?></span>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-4">
                        <label for="username" class="block font-bold mb-2 text-white">Username</label>
                        <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Username" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block font-bold mb-2 text-white">Email</label>
                        <input type="text" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Email" required>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block font-bold mb-2 text-white">Password</label>
                        <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Password" required> 
                    </div>
                    <div class="mb-6">
                        <label for="password2" class="block font-bold mb-2 text-white">Confirm Password</label>
                        <input type="password" id="password2" name="password2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" name="register" class="bg-green-600 text-white py-2 px-4 rounded">Register</button>
                </form>
                <a href="signInPage.php">
                    <img src="./assets/nextButton.png" id="nextButton" class="h-10 mt-5 float-right" onmouseover="changeImage(1)" onmouseout="changeImage(2)">
                </a>
            </div>
        </div>
    </body>
    </html>
