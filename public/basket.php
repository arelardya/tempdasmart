<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signInPage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "dasmart_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get items from the user's basket, including the quantity
$query = "SELECT items.name, items.price, baskets.quantity FROM baskets 
          JOIN items ON baskets.item_id = items.id 
          WHERE baskets.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$basketItems = [];
$totalPrice = 0;
while ($row = $result->fetch_assoc()) {
    $basketItems[] = $row;
    $totalPrice += $row['price'] * $row['quantity'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="min-h-screen flex flex-col">

    <nav>
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
                        <a id="profile"
                            class="px-3 md:px-5 pb-2 text-black relative after:block after:bg-green-800 after:content-[''] after:h-[3px] after:w-0 after:transition-all after:duration-300 after:ease-in-out after:absolute after:left-0 after:bottom-0 hover:after:w-full"
                            href="./profilePage.html">Profile</a>
                    </li>
                    <li>
                        <a id="basket" class="hidden p-3 px-4 text-white bg-green-800 rounded-3xl hover:bg-green-600"
                            href="./basket.php">Basket</a>
                    </li>
                    <li>
                        <a id="signin" class="hidden p-3 px-4 text-white bg-green-800 rounded-3xl hover:bg-green-600"
                            href="./signInPage.php">Sign In</a>
                    </li>
                </ul>
            </div>
        </nav>
    </nav>

    <h1 class="ml-6 text-6xl p-4 font-logo">Checkout</h1>

    <div class="bg-gray-200 rounded-3xl mx-10 p-6 flex-grow">
    <?php if (!empty($basketItems)): ?>
        <div class="flex flex-wrap justify-center"> <!-- Flex container for horizontal layout -->
            <?php foreach ($basketItems as $item): ?>
                <div class="bg-white w-8/12 md:w-6/12 lg:w-4/12 mx-5 rounded-3xl hover:scale-105 hover:shadow-2xl transition-transform duration-300 ease-in-out p-5 m-5">
                    <h3 class="flex justify-center pb-5 text-xl font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="flex justify-center pb-5">Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                    <p class="flex justify-center pb-5">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Your basket is empty.</p>
    <?php endif; ?>

    <h2 class="text-3xl ml-5 pb-3">Total: $<?php echo number_format($totalPrice, 2); ?></h2>

    <form action="thanks.html">
        <button type="submit" class="text-white p-3 px-4 bg-green-800 w-8/12 md:w-6/12 lg:w-4/12 mx-auto rounded-3xl hover:scale-105 hover:bg-green-600 transition-transform duration-300 ease-in-out">
            Proceed to Checkout
        </button>
    </form>
</div>


    <!-- Move the footer inside the body and ensure it appears at the bottom -->
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
