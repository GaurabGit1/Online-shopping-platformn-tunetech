<?php
session_start();
include "../public/header.php";
include "../public/products_handler.php";
?>
<body>
  <main class="product-container">
    <?php
    if ($result->num_rows > 0) {
        echo '<div class="product-grid">';
        // Display each product
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="product-card">
              <a href="product_details.php?id=' . $row['id'] . '">
                <img src="' . htmlspecialchars($row['imageUrl']) . '" alt="' . htmlspecialchars($row['name']) . '">
              </a>
              <h3>' . htmlspecialchars($row['name']) . '</h3>
              <p>Price: NPR ' . number_format($row['price'], 2) . '</p>
              <form action="../user/add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="' . $row['id'] . '">
                <button class="button add-to-cart" type="submit">Add to Cart</button>
              </form>
            </div>';
        }
        echo '</div>';
    } else {
        echo "<p>No products found!</p>";
    }
    ?>
  </main>
<?php include "../public/footer.php"; ?>
</body>
</html>
