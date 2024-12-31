<?php
session_start();
include "../public/db.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET status = ?, last_updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order status.']);
    }
    exit();
}

// Fetch sales history
$query = "
    SELECT 
        o.id AS order_id,
        p.name AS product_name,
        o.order_quantity,
        u.name AS customer_name,
        ch.address,
        o.ordered_at,
        o.status
    FROM orders o
    INNER JOIN products p ON o.product_id = p.id
    INNER JOIN users u ON o.user_id = u.id
    INNER JOIN checkout ch ON o.user_id = ch.user_id
    ORDER BY o.ordered_at DESC
";
$result = $conn->query($query);
?>

<?php include "../admin/admin_header.php" ?>
<body>
    <main>
        <h1>Sales History</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Order Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="order-rows">
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr data-order-id="<?php echo $order['order_id']; ?>">
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                            <td><?php echo htmlspecialchars($order['ordered_at']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>
                                <select class="status-dropdown">
                                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Confirmed" <?php echo $order['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?php echo $order['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No sales history found!</p>
        <?php endif; ?>
    </main>
    <?php include "../public/footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('change', '.status-dropdown', function () {
            const orderRow = $(this).closest('tr');
            const orderId = orderRow.data('order-id');
            const newStatus = $(this).val();

            $.post('sales_page.php', { order_id: orderId, status: newStatus }, function (response) {
                alert(response.message);
                if (response.success) {
                    orderRow.find('td:nth-child(7)').text(newStatus);
                }
            }, 'json');
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
