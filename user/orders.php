<?php
session_start();
include "../public/db.php"; // Include the database connection
include "../public/header.php"; // Include the header

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your orders!'); window.location.href='../user/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$query = "
    SELECT 
        orders.id AS order_id, 
        products.name AS product_name, 
        orders.order_quantity AS order_quantity, 
        orders.total_price, 
        orders.status 
    FROM orders 
    JOIN products ON orders.product_id = products.id 
    WHERE orders.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="orders-container">
        <h1>Your Order History</h1>
        <?php if ($result->num_rows > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_quantity']); ?></td>
                            <td>RS <?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found!</p>
        <?php endif; ?>
    </div>
    <?php include "../public/footer.php"; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
