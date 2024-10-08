<?php
// Start session and enable error reporting
session_start();
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

// Initialize the message variable to avoid undefined warnings
$message = '';

// Database connection
$conn = mysqli_connect("localhost", "root", "", "dasmart_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch user details from the database using prepared statements
    $query = "SELECT id, username, email, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $user_username, $user_email, $hashedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Verify password
    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        // Store user info in session
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user_username;
        $_SESSION['email'] = $user_email;

        // Redirect to dashboard or homepage after login
        header("Location: index.html");
        exit();
    } else {
        $message = "Invalid username or password.";
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
</head>
<body class="font-[Poppins]">
    <div class="min-h-screen" style="background-image: url(./assets/bg_signIn.jpg);">
    <nav class="flex justify-between items-center bg-green-800 bg-opacity-70">
            <div>
                <a href="./index.html">
                    <img class="w-20 h-20" src="./assets/logo.png" alt="">
                </a>
            </div>
            <div>
                <ul class="flex items-center pr-5">
                    <li>
                        <a class="px-5 pb-3 text-white hover:underline" href="./market.php">Marketplace</a>
                    </li>
                    <li>
                        <a class="px-5 pb-3 text-white hover:underline" href="./aboutus.html">About Us</a>
                    </li>
                    <li>
                        <a class="p-3 px-4 text-green-800 bg-white rounded-3xl hover:bg-green-600" href="./adminlogin.html">Admin</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="flex justify-center items-center mt-3">
            <div class="bg-gray-300 bg-opacity-50 rounded-lg shadow-md p-10 w-9/12 mb-12">
                <h2 class="text-2xl font-bold mb-4 text-center text-white">Sign In</h2>
                <form id="signInForm" method="POST" action="signInPage.php">
                    <div class="mb-4">
                        <input type="hidden" name="login_type" value="user">
                        <label for="username" class="block font-bold mb-2 text-white">Username</label>
                        <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Username" required>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block font-bold mb-2 text-white">Password</label>
                        <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Password" required>
                    </div>
                    <div class="flex flex-row">
                        <p class="mr-1">New Here?</p>
                        <a href="signUpPage.php" class="text-green-800">Register Now</a>
                    </div>
                    <p class="text-red-500 text-center mt-4"><?php echo $message; ?></p> <!-- Message display -->
                    <button type="submit" class="h-10 mt-5 float-right cursor-pointer">
                        <img src="./assets/nextButton.png" id="nextButton" onmouseover="changeImage(1)" onmouseout="changeImage(2)" class="h-10">
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
