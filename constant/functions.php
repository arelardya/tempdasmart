<?php
error_reporting(E_ALL); // Show all errors
ini_set('display_errors', 1); // Display errors on the page

session_start(); // Start the session

// Check if 'user_info' is in the query parameters
if (isset($_GET['user_info']) && $_GET['user_info'] === 'true') {
    // Return the user information from session as JSON
    $userInfo = [];

    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
        $userInfo['username'] = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $userInfo['email'] = isset($_SESSION['email']) ? $_SESSION['email'] : null;
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($userInfo);
    exit();
}

// The rest of your existing code
$message = ''; 

// Database connection
$conn = mysqli_connect("localhost", "root", "", "dasmart_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Admin credentials (example, should ideally be in the database)
$adminUsername = "admin";
$adminPassword = "admin123"; // This is plain text, not hashed for now

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_type = isset($_POST['login_type']) ? $_POST['login_type'] : '';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($login_type === 'admin') {
        handleAdminLogin($username, $password);
    } elseif ($login_type === 'user') {
        if (handleUserLogin($username, $password)) {
            header("Location: index.html"); // Redirect to user homepage
            exit();
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Login type is not specified.";
    }
}

// Function to handle admin login
function handleAdminLogin($username, $password) {
    global $adminUsername, $adminPassword;

    // Validate admin credentials
    if ($username === $adminUsername && $password === $adminPassword) {
        // Set session variables
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['isAdmin'] = true;

        // Redirect to the admin dashboard
        header("Location: ../public/menuAdmin.php");
        exit();
    } else {
        // Redirect back to the admin login page with an error
        header("Location: adminLogin.html?error=Invalid%20username%20or%20password");
        exit();
    }
}

// Function to handle user login
function handleUserLogin($username, $password) {
    global $conn;

    // Prepare and execute the query to fetch the user
    $query = "SELECT id, username, email, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['isLoggedIn'] = true; // Set session variable
        $_SESSION['username'] = $username; // Store username
        $_SESSION['email'] = $email; // Store email
        return true; // Login successful
    } else {
        return false; // Invalid credentials
    }
}

// Function to handle admin registration (for reference, if needed)
function regisAdmins($data) {
    global $conn;

    // Extracting data
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $password2 = $data['password2'];

    // Check if passwords match
    if ($password !== $password2) {
        return false; // Passwords do not match
    }

    // Check if the username or email already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return false; // Username or email already exists
    }
    
    mysqli_stmt_close($stmt); // Close the statement

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt); // Close the statement
        return true; // Registration successful
    } else {
        mysqli_stmt_close($stmt); // Close the statement
        return false; // Registration failed
    }
}

// Function to get user information
function getUserInfo() {
    header('Content-Type: application/json'); // Set content type to JSON
    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
        echo json_encode([
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'] ?? null // Include email if available
        ]);
    } else {
        echo json_encode(null); // User is not logged in
    }
}

// Function to handle logout
function logout() {
    session_destroy(); // Destroy the session
    header("Location: ../public/index.html"); // Redirect to homepage or login page
    exit();
}

// Check if logout is requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}
?>
