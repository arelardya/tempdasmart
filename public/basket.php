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
</head>
<body>

    <nav>
        <!-- Nav content -->
    </nav>

    <h1 class="text-5xl p-5">Your Basket</h1>
    <div class="p-5">
        <?php if (!empty($basketItems)): ?>
            <?php foreach ($basketItems as $item): ?>
                <div class="item p-5 border-b">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your basket is empty.</p>
        <?php endif; ?>
    </div>
    
    <h2>Total: $<?php echo number_format($totalPrice, 2); ?></h2>
    <form action="checkout.php" method="POST">
        <button type="submit" class="bg-green-600 text-white p-3 rounded-lg hover:bg-green-500">Proceed to Checkout</button>
    </form>

    <!-- Footer -->
</body>
</html>
