<?php
session_start();
include "../public/header.php";
include "../public/db.php";

// Get product ID from query parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found, show an error
if (!$product) {
    echo "<p>Product not found!</p>";
    include "../public/footer.php";
    exit();
}

// Default image fallback
$imageUrl = !empty($product['imageUrl']) ? $product['imageUrl'] : '../uploads/default-product.png';
?>
<body>
  <main class="product-details-section">

    <div class="product-details-container">
      <!-- Left Image Section -->
      <div class="product-images">
        <div class="main-image">
          <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="thumbnail-images">
          <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Thumbnail">
        </div>
      </div>

      <!-- Right Details Section -->
      <div class="product-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-features"><?php echo htmlspecialchars($product['description']); ?></p>
        <div class="product-code">Code: <?php echo htmlspecialchars($product['id']); ?></div>
        <div class="product-price">RS <?php echo number_format($product['price'], 2); ?></div>

        <!-- Color Options -->
        <div class="product-colors">
          <span>Color:</span>
          <span class="color-circle" style="background-color: #ccc;"></span>
          <span class="color-circle" style="background-color: #333;"></span>
        </div>

        <!-- Quantity Selector -->
        <div class="quantity-selector">
          <span>Quantity:</span>
          <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
          <input type="number" id="quantity" name="quantity" value="1" min="1">
          <button class="quantity-btn" onclick="increaseQuantity()">+</button>
        </div>

        <!-- Action Buttons -->
        <div class="product-actions">
          <form action="../user/add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="hidden" name="quantity" id="form-quantity" value="1">
            <button type="submit" class="add-to-cart">Add to Cart</button>
          </form><br>
          <button 
            type="button" 
            class="buy-now" 
            onclick="redirectToCheckout(<?php echo $product_id; ?>)">
            Buy Now
          </button>
        </div>
      </div>
    </div>

    <!-- Product Description -->
    <div class="product-description">
      <h2>Description</h2>
      <p><?php echo htmlspecialchars($product['description']); ?></p>
    </div>
  </main>

  <script>
    // JavaScript for quantity management
    const quantityInput = document.getElementById('quantity');
    const formQuantity = document.getElementById('form-quantity');

    function increaseQuantity() {
        quantityInput.stepUp();
        formQuantity.value = quantityInput.value;
    }

    function decreaseQuantity() {
        quantityInput.stepDown();
        formQuantity.value = quantityInput.value;
    }

    function redirectToCheckout(productId) {
        const quantity = quantityInput.value;
        window.location.href = `../user/checkout.php?product_id=${productId}&quantity=${quantity}`;
    }
  </script>
</body>
<?php include "../public/footer.php"; ?>
