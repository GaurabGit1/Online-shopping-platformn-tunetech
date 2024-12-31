<?php
include "../admin/admin_header.php";
include "../public/db.php"; // Include the database connection

// Fetch categories
$categories = [];
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<body>
    <div class="container">
        <h1>Add a New Product</h1>
        <form action="add_products_handler.php" method="POST" enctype="multipart/form-data" class="form">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category_id" required>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" required>
            </div>

            <button type="submit" class="button">Add Product</button>
        </form>
    </div>
    <?php include "../public/footer.php"; ?>
</body>
</html>
