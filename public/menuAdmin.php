<?php
// Start the session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: adminLogin.html"); // Redirect to login page if not logged in as admin
    exit();
}

// Handle the logout functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy all session data
    header("Location: index.html"); // Redirect to login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="font-[Poppins]">
    <nav class="flex justify-between items-center">
        <div>
            <a href="./index.html">
                <img class="w-20 h-20" src="./assets/logoijo.png" alt="">
            </a>
        </div>
        <div>
            <ul class="flex items-center pr-5">
                <li>
                    <a class="px-5 pb-3 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full" 
                    href="./market.php">Marketplace</a>
                </li>
                <li>
                    <a class="px-5 pb-3 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full" 
                    href="./aboutus.html">About Us</a>
                </li>
                <li>
                    <!-- Logout Button -->
                    <form action="menuAdmin.php" method="POST" style="display:inline;">
                        <button type="submit" name="logout" class="p-3 px-4 text-white bg-red-600 rounded-3xl hover:bg-red-400">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto p-10 grid grid-cols-2 gap-4">
        <ul class="bg-gray-200 p-4 border border-black"><a href="addItem.php">Add Items</a></ul>
        <ul class="bg-gray-200 p-4 border border-black">View Feedback</ul>
        <ul class="bg-gray-200 p-4 border border-black">Coming Soon</ul>
        <ul class="bg-gray-200 p-4 border border-black">Coming Soon</ul>
    </div>

    <footer class="bg-green-800 p-10 grid grid-cols-1 md:grid-cols-3 text-white">
        <div>
            <p>Dasmart & co.</p>
            <p>
                Committed to bringing you the best of goods.<br>&copy; 2020, dasmart&co. All Rights Reserved.
            </p>
        </div>
        <div class="flex justify-center">
            <img class="w-[20%]" src="./assets/logo.png" alt="logo">
        </div>
        <div class="flex justify-end text-right">
            <p class="ml-16">
                (+62)824-2535-3252-6366<br>
                dasmart@daskomlab.com<br>
                Berharap Bersama St. Tultington
            </p>
        </div>
    </footer>
    </footer>
</body>
</html>
