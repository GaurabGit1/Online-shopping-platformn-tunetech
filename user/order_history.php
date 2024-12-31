<?php
include "../public/loggedin_session.php"; // Include session logic
include "../public/db.php"; // Include database connection

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders from the database
$query = "
    SELECT 
        orders.id AS order_id, 
        orders.order_date, 
        orders.total_amount, 
        orders.status 
    FROM orders 
    WHERE orders.user_id = ?
    ORDER BY orders.order_date DESC";
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
  <title>Order History - TuneTech</title>
  <link rel="stylesheet" href="../public/style.css">
  <style>
   
  </style>
</head>
<body>
  <!-- Navbar -->
  <?php include "../public/header.php"; ?>

  <!-- Order History Content -->
  <div class="orders-container">
    <div class="order-header">
      <h2>Your Order History</h2>
    </div>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="order-item">
          <p><span>Order ID:</span> <?php echo $row['order_id']; ?></p>
          <p><span>Date:</span> <?php echo $row['order_date']; ?></p>
          <p><span>Total:</span> NPR <?php echo number_format($row['total_amount'], 2); ?></p>
          <p><span>Status:</span> <?php echo ucfirst($row['status']); ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No orders found.</p>
    <?php endif; ?>
    <?php $stmt->close(); ?>
  </div>
</body>
</html>
