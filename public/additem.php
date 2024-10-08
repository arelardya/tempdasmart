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
</head>
<body>
    <nav class="flex justify-between items-center">
        <!-- Navigation bar content -->
    </nav>

    <h1>Add a New Item</h1>

    <!-- Display success or error message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- Add Item Form -->
    <form action="addItem.php" method="POST">
        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" required><br>

        <label for="itemPrice">Price:</label>
        <input type="number" id="itemPrice" name="itemPrice" step="0.01" required><br>

        <label for="itemCategory">Category:</label>
        <select id="itemCategory" name="itemCategory" required>
            <option value="vegetable">Vegetable</option>
            <option value="fruit">Fruit</option>
            <option value="other">Other</option>
        </select><br>

        <input type="submit" value="Add Item">
    </form>

    <!-- Footer content -->
</body>
</html>
