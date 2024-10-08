<?php
session_start();

// Assuming you have the user ID stored in the session when they log in
if (!isset($_SESSION['user_id'])) {
    header("Location: signInPage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "dasmart_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an item was added to the basket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Check if item already exists in the user's basket
    $query = "SELECT * FROM baskets WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If item exists, update the quantity
        $query = "UPDATE baskets SET quantity = quantity + 1 WHERE user_id = ? AND item_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $item_id);
        $stmt->execute();
        $message = "Item quantity updated in basket.";
    } else {
        // Otherwise, insert a new row
        $query = "INSERT INTO baskets (user_id, item_id, quantity) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $item_id);
        $stmt->execute();
        $message = "Item added to basket.";
    }
    
    $stmt->close();
}

// Fetch all items from the items table for display in the marketplace
$query = "SELECT id, name, price, category FROM items";
$result = $conn->query($query);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
} else {
    $message = "No items available in the marketplace.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="./script.js"></script>

    <style>
        /* Additional styling for the slider */
        #sliderContainer {
            overflow: hidden;
            position: relative;
        }
        #slider {
            transition: transform 0.5s ease-in-out;
        }
    </style>
</head>

<body class="font-[Poppins]">
<nav class="flex flex-wrap justify-between items-center p-5 bg-white shadow-md sticky top-0 z-10">
        <div>
            <a href="./index.html">
                <img class="w-16 h-16 md:w-20 md:h-20" src="./assets/logoijo.png" alt="">
            </a>
        </div>
        <button id="burgerMenuButton" class="block md:hidden p-3">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <div id="navLinks" class="w-full md:w-auto hidden md:flex flex-col md:flex-row items-center pr-5">
            <ul class="flex flex-col md:flex-row items-center">
                <li class="py-2 md:py-0">
                    <a class="px-3 md:px-5 pb-2 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full" 
                    href="./market.php">Marketplace</a>
                </li>
                <li class="py-2 md:py-0">
                    <a class="px-3 md:px-5 pb-2 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full" 
                    href="./aboutus.html">About Us</a>
                </li>
                <li class="py-2 md:py-0">
                    <a id="profile" class="px-3 md:px-5 pb-2 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full" 
                    href="./profilePage.html">Profile</a>
                </li>
                <li>
                    <a id="basket" class="hidden p-3 px-4 text-white bg-green-800 rounded-3xl hover:bg-green-600" href="./basket.php">Basket</a>
                </li>    
                <li>
                    <a id="signin" class="p-3 px-4 text-white bg-green-800 rounded-3xl hover:bg-green-600" href="./signInPage.php">Sign In</a>
                </li>   
            </ul>
        </div>
    </nav>

    <!-- Section 1 -->
    <div>
        <h1 class="text-7xl ml-4 p-5 font-logo">
            Da Marketplace
        </h1>
    </div>

    <!-- Advertisement -->
    <div id="sliderContainer" class="w-full">
        <ul id="slider" class="flex w-full"></ul>
    </div>

    <!-- Dynamic Items -->
    <?php if (!empty($message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="items-list">
        <?php if (!empty($items)): ?>
            <ul>
                <?php foreach ($items as $item): ?>
                <li class="item w-96 p-5">
                    <div class="border rounded-lg p-5 item-details">
                        <h2 class="mt-2 text-2xl font-bold text-gray-700"><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p>Price: <?php echo htmlspecialchars($item['price']); ?></p>
                        <p>Category: <?php echo htmlspecialchars($item['category']); ?></p>

                        <!-- Add to Cart form -->
                        <form method="POST" action="market.php">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="add-to-cart mt-4 p-2 bg-green-600 text-white rounded-lg">Add to Cart</button>
                        </form>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No items found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-green-800 p-10 grid grid-cols-1 z-20 relative md:grid-cols-3 text-white gap-5">
        <div>
            <p>
                Dasmart & co.
            </p>
            <p>
                Committed to bringing you the best of goods.<br>&copy; 2020, dasmart&co. All Rights Reserved
            </p>
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

    <script>
        // JavaScript for the slider
        const slider = document.getElementById('slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        let currentPosition = 0;

        prevBtn.addEventListener('click', () => {
            // Move the slider to the left
            if (currentPosition > 0) {
                currentPosition -= 1;
                slider.style.transform = `translateX(-${currentPosition * 100}%)`;
            }
        });

        nextBtn.addEventListener('click', () => {
            // Move the slider to the right
            if (currentPosition < slider.children.length - 1) {
                currentPosition += 1;
                slider.style.transform = `translateX(-${currentPosition * 100}%)`;
            }
        });
    </script>
</body>
</html>
