<?php
session_start();
include "ad_handler.php";
include "../admin/admin_header.php";
include "../public/db.php"; // Include the database connection

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
// Fetch products with category names. Handle cases where the category_id is NULL.
$sql = "SELECT products.*, categories.name AS category_name 
        FROM products 
        LEFT JOIN categories ON products.category_id = categories.id";
$result = $conn->query($sql);
?>

    <h1>Admin Dashboard</h1>
    <a class="add-button" href="add_products.php">Add New Product</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>NPR <?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><img src="<?= htmlspecialchars($row['imageUrl'], ENT_QUOTES, 'UTF-8') ?>" alt="Product Image" width="50"></td>
                        <td><?= $row['category_name'] ?? 'Uncategorized' ?></td>
                        <td class="action-buttons">
                            <a href="update_products.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="admin_dashboard.php?delete_id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php include "../public/footer.php"; ?>
</body>
</html>

<?php
$conn->close();
?>
