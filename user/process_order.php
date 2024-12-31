<?php
session_start();
include "../public/db.php"; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed!'); window.location.href='../user/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Retrieve data from POST
$cart_items = isset($_POST['cart_items']) ? json_decode($_POST['cart_items'], true) : [];
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0.0;
$street = isset($_POST['street']) ? $_POST['street'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$payment_mode = isset($_POST['payment_mode']) ? $_POST['payment_mode'] : '';

// Check if the request is from a "Buy Now" action
if (isset($_POST['product_id'], $_POST['quantity']) && empty($cart_items)) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Fetch product details
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product || $quantity > $product['quantity']) {
        $message = !$product ? 'Invalid product.' : 'Requested quantity exceeds available stock.';
        echo "<script>alert('$message'); window.location.href='../public/shop.php';</script>";
        exit;
    }

    $cart_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
    ];
    $total = $product['price'] * $quantity;
}

if (empty($cart_items) || $total <= 0 || empty($street) || empty($city) || empty($payment_mode)) {
    echo "<script>alert('Invalid input or missing information.'); window.location.href='checkout.php';</script>";
    exit;
}

$full_address = $street . ', ' . $city; // Combine street and city into one address field

$conn->begin_transaction();
try {
    foreach ($cart_items as $item) {
        // Fetch product details
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $item['product_id']);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$product || $item['quantity'] > $product['quantity']) {
            throw new Exception('Product stock issue.');
        }

        // Insert into orders table
        $order_query = "INSERT INTO orders (user_id, product_id, order_quantity, total_price, ordered_at) VALUES (?, ?, ?, ?, NOW())";
        $order_stmt = $conn->prepare($order_query);
        $order_price = $product['price'] * $item['quantity'];
        $order_stmt->bind_param("iiid", $user_id, $item['product_id'], $item['quantity'], $order_price);
        $order_stmt->execute();
        $order_stmt->close();

        // Update product quantity
        $update_query = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Insert into checkout table
    $checkout_query = "INSERT INTO checkout (user_id, address, payment_method, total, created_at) VALUES (?, ?, ?, ?, NOW())";
    $checkout_stmt = $conn->prepare($checkout_query);
    $checkout_stmt->bind_param("issd", $user_id, $full_address, $payment_mode, $total);
    $checkout_stmt->execute();
    $checkout_stmt->close();

    $conn->commit();

    // Redirect to thank you page
    echo "<script>alert('Order placed successfully!'); window.location.href='thankyou.php';</script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Order placement failed. Please try again.'); window.location.href='checkout.php';</script>";
}
?>
