<?php
// header.php
include "../public/loggedin_session.php"; // Include session logic
include "../public/db.php"; // Include database connection

$user_id = $_SESSION['user_id'] ?? null; // Retrieve logged-in user's ID, or null if not logged in
$user_logged_in = isset($user_id); // Check if the user is logged in
$user_profile_picture = 'default-profile.png'; // Default profile picture

// Fetch user's profile picture if logged in
if ($user_logged_in) {
    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_picture);
    if ($stmt->fetch()) {
        $user_profile_picture = $profile_picture ?: 'default-profile.jpg';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TuneTech</title>
  <link rel="stylesheet" href="../public/style.css">
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="logo"><a href="../public/index.php">TuneTech</a></div>
    <nav>
      <ul class="nav-links">
        <li><a href="../public/index.php">Home</a></li>
        <li><a href="../public/shop.php">Shop</a></li>
        <li><a href="../public/products.php">Products</a></li>
        <li><a href="../public/contact.php">Contact</a></li>
      </ul>
    </nav>
    <div class="icons">
      <?php if ($user_logged_in): ?>
        <!-- Display profile picture if user is logged in -->
        <a href="../user/profile.php">
          <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="profile-icon">
        </a>
      <?php else: ?>
        <!-- Display default login icon -->
        <a href="../user/login.php"><i class="user-icon">👤</i></a>
      <?php endif; ?>
      <a href="#" id="open-cart"><i class="cart-icon">🛒</i></a>
    </div>
  </header>

  <!-- Floating Cart Sidebar -->
  <div class="cart-sidebar" id="cart-sidebar">
    <div class="cart-header">
      <span>Your Cart</span>
      <button class="close-cart" id="close-cart">✖</button>
    </div>
    <div class="cart-content">
      <?php
      if ($user_logged_in) {
          // Fetch cart items for the logged-in user
          $sql = "
              SELECT 
                  cart.id AS cart_id, 
                  products.id AS product_id,
                  products.name, 
                  products.price, 
                  products.imageUrl, 
                  cart.quantity 
              FROM cart 
              INNER JOIN products ON cart.product_id = products.id 
              WHERE cart.user_id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              $total = 0;
              $cart_items = []; // Array to store the cart items
              while ($row = $result->fetch_assoc()) {
                  $total += $row['price'] * $row['quantity'];
                  $cart_items[] = [
                      'product_id' => $row['product_id'],
                      'quantity' => $row['quantity']
                  ];
                  ?>
                  <div class="cart-item">
                      <img src="<?php echo htmlspecialchars($row['imageUrl']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                      <div class="cart-item-info">
                          <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                          <p>Price: NPR <?php echo number_format($row['price'], 2); ?></p>
                          <div>
                              <form action="../user/update_cart_quantity.php" method="POST">
                                  <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                  <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                                  <button type="submit">Update</button>
                              </form>
                          </div>
                      </div>
                      <div class="cart-item-actions">
                          <form action="../user/delete_from_cart.php" method="POST">
                              <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                              <button class="btn-delete" type="submit">Delete</button>
                          </form>
                      </div>
                  </div>
                  <?php
              }
              ?>
              <div class="cart-total">
                  <p>Total: NPR <?php echo number_format($total, 2); ?></p>
              </div>
              <!-- Proceed to Payment Button with cart items data -->
              <form action="../user/checkout.php" method="POST">
                  <!-- Serialize the cart items and pass them as hidden input -->
                  <input type="hidden" name="cart_items" value='<?php echo json_encode($cart_items); ?>'>
                  <button type="submit" class="btn-checkout">Proceed to Payment</button>
              </form>
              <?php
          } else {
              echo "<p class='empty-cart'>Your cart is empty!</p>";
          }
          $stmt->close();
      } else {
          echo "<p class='empty-cart'>Please log in to view your cart.</p>";
      }
      ?>
    </div>
  </div>

  <!-- JavaScript for Cart Toggle -->
  <script>
    const openCart = document.getElementById('open-cart');
    const closeCart = document.getElementById('close-cart');
    const cartSidebar = document.getElementById('cart-sidebar');

    openCart.addEventListener('click', () => {
      cartSidebar.classList.add('active');
    });

    closeCart.addEventListener('click', () => {
      cartSidebar.classList.remove('active');
    });
  </script>
</body>
</html>
