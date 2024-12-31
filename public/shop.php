<?php
session_start();
include "../public/db.php"; // Include the database connection
include "../public/header.php";

// Fetch categories
$categories = [];
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch products based on category
$category_id = $_GET['category_id'] ?? null;
$products = [];
if ($category_id) {
    $product_sql = "SELECT * FROM products WHERE category_id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    while ($product = $product_result->fetch_assoc()) {
        $products[] = $product;
    }
    $stmt->close();
} else {
    // Fetch all products if no category is selected
    $product_sql = "SELECT * FROM products";
    $product_result = $conn->query($product_sql);
    if ($product_result && $product_result->num_rows > 0) {
        while ($product = $product_result->fetch_assoc()) {
            $products[] = $product;
        }
    }
}
?>

<div class="shop-container">
    <div class="sidebar">
        <h2>Categories</h2>
        <ul class="category-list">
            <li><a href="shop.php">All Categories</a></li>
            <?php foreach ($categories as $category) { ?>
                <li><a href="shop.php?category_id=<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a></li>
            <?php } ?>
        </ul>
    </div>

    <div class="product-grid">
        <?php if (!empty($products)) { ?>
            <?php foreach ($products as $product) { ?>
                <div class="product-card">
                    <a href="product_details.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['imageUrl']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>Price: NPR <?php echo number_format($product['price'], 2); ?></p>
                    <form action="../user/add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button class="button add-to-cart" type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No products found in this category.</p>
        <?php } ?>
    </div>
</div>

<?php include "../public/footer.php"; ?>
