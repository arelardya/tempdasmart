<?php
// Start session if not started already
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "dasmart_db");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize success/error message variables
$successMessage = '';
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemCategory = $_POST['itemCategory'];

    // Insert the item into the database
    $query = "INSERT INTO items (name, price, category) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sds", $itemName, $itemPrice, $itemCategory); // Corrected binding types

    if (mysqli_stmt_execute($stmt)) {
        // Success message
        $successMessage = "Item added successfully!";
    } else {
        // Error handling
        $errorMessage = "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./script.js"></script>
</head>
<body>
<nav class="flex justify-between items-center">
        <div>
            <a href="./index">
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
                    <!-- <a class="p-3 px-4 text-white bg-green-800 rounded-3xl hover:bg-green-600" href="/public/signInPage.html">Sign In</a> -->
                </li>
            </ul>
        </div>
    </nav>
    <h1 class="text-2xl font-bold mb-4 text-center">Add a New Item</h1>

    <!-- Display success or error message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- Add Item Form -->
    <form action="addItem.php" method="POST" class="flex justify-center items-center w-full px-10 mb-10">
        <div class="bg-gray-300 bg-opacity-50 rounded-lg shadow-md p-10 w-9/16">
            <table class="w-full">
                <tr class="flex items-center mb-4">
                    <th class="w-40"><label for="itemName">Item Name</label></th>
                    <td><input type="text" id="itemName" name="itemName" required class="w-full"><br></td>
                </tr>
                <tr class="flex items-center mb-4">
                    <th class="w-40"><label for="itemPrice">Price</label></th>
                    <td><input type="number" id="itemPrice" name="itemPrice" step="0.01" required class="w-full"><br></td>
                </tr>
                <tr class="flex items-center mb-4">
                    <th class="w-40"><label for="itemCategory">Category</label></th>
                    <td>
                        <select id="itemCategory" name="itemCategory" required class="w-full">
                            <option value="vegetable">Vegetable</option>
                            <option value="fruit">Fruit</option>
                            <option value="other">Other</option>
                        </select><br>
                    </td>
                </tr>    
            </table>
            <input type="submit" value="Add Item" class="w-full text-xl font-semibold text-green-600">
        </div>
    </form>
    
    <footer class="bg-green-800 p-10 grid grid-cols-1 md:grid-cols-3 text-white gap-5 mt-5">
        <div>
            <p>Dasmart & co.</p>
            <p>Committed to bringing you the best of goods.<br>&copy; 2020, dasmart&co. All Rights Reserved</p>
        </div>
        <div class="flex justify-center">
            <img class="w-[20%]" src="./assets/logo.png" alt="">
        </div>
        <div class="flex justify-end text-right">
            <p class="ml-16">
                (+62)824-2535-3252-6366<br>
                dasmart@daskomlab.com<br>
                Berharap Bersama St. Tultington
            </p>
        </div>
    </footer>
</body>
</html>