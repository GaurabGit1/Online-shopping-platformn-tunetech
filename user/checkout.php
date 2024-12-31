<?php
session_start();
include "../public/header.php";
include "../public/db.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed to checkout!'); window.location.href='../user/login.php';</script>";
    exit;
}

$cart_items = [];

// Handle "Buy Now" redirection with product_id and quantity from query parameters
if (isset($_GET['product_id'], $_GET['quantity'])) {
    $product_id = intval($_GET['product_id']);
    $quantity = intval($_GET['quantity']);

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Validate the product and stock
    if (!$product || $quantity > $product['quantity']) {
        $message = !$product ? 'Invalid product.' : 'Requested quantity exceeds available stock.';
        echo "<script>alert('$message'); window.location.href='../public/shop.php';</script>";
        exit;
    }

    $cart_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'name' => $product['name'],
        'price' => $product['price'],
    ];
} else {
    // Handle cart-based checkout
    $user_id = $_SESSION['user_id'];

    // Retrieve cart items from the cart table
    $query = "
        SELECT c.product_id, c.quantity, p.name, p.price 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity'],
            'name' => $row['name'],
            'price' => $row['price'],
        ];
    }
    $stmt->close();
}

if (empty($cart_items)) {
    echo "<script>alert('Your cart is empty!'); window.location.href='../public/shop.php';</script>";
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<body>
<main class="checkout-section">
    <h1>Checkout</h1>
    <div class="checkout-container">
        <!-- Product Summary -->
        <div class="checkout-summary">
            <h2>Product Summary</h2>
            <?php foreach ($cart_items as $item): ?>
                <p><strong>Product:</strong> <?php echo htmlspecialchars($item['name']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
                <p><strong>Price:</strong> NPR <?php echo number_format($item['price'], 2); ?></p>
            <?php endforeach; ?>
            <p><strong>Total:</strong> NPR <?php echo number_format($total, 2); ?></p>
        </div>

        <!-- Payment and Address Form -->
        <div class="checkout-form">
            <h2>Shipping & Payment</h2>
            <form action="process_order.php" method="POST">
                <input type="hidden" name="cart_items" value='<?php echo json_encode($cart_items); ?>'>
                <input type="hidden" name="total" value="<?php echo $total; ?>">

                <div class="form-group">
                    <label for="street">Street Address:</label>
                    <input type="text" id="street" name="street" required placeholder="Enter your street address">
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required placeholder="Enter your city">
                </div>
                <div class="form-group">
                    <label for="payment_mode">Payment Mode:</label>
                    <select id="payment_mode" name="payment_mode" required>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="confirm-order">Confirm Order</button>
            </form>
        </div>
    </div>
</main>
</body>

<?php include "../public/footer.php"; ?>
