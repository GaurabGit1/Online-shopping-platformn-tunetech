<?php
session_start();
include "../public/db.php"; // Database connection

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); // Remove unnecessary spaces
    $password = trim($_POST['password']);

    // Check for admin credentials using the name field
    $query = "SELECT id, name, password FROM admins WHERE BINARY name = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $db_name, $db_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Store admin information in the session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $db_name;
            $_SESSION['admin_logged_in'] = true;

            // Redirect to the admin profile
            header("Location: admin_profile.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid password. Please try again.";
        }
    } else {
        // Invalid name
        $error = "Invalid name. Please check your credentials.";
    }

    $stmt->close();
}

$conn->close();
?>

<?php include "../admin/admin_header.php"; ?>
<body>
  <main class="signup-section">
    <div class="signup-container">
      <!-- Left Placeholder Image -->
      <div class="signup-image">
        <img src="../uploads/1.png" alt="Admin Login Placeholder">
      </div>

      <!-- Admin Login Form -->
      <div class="signup-form">
        <h2>Admin Login</h2>
        <!-- Display error message if credentials are incorrect -->
        <?php if (!empty($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <form method="POST" action="admin_login.php">
          <div class="form-group">
            <input type="text" name="name" placeholder="Enter your name here" required>
          </div>
          <div class="form-group password-field">
            <input type="password" name="password" id="password" placeholder="Enter your password here" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <button type="submit" class="signup-btn">Log In</button>
        </form>
        <p>Don't have an admin account? <a href="admin_signup.php">Sign Up</a></p>
      </div>
    </div>
  </main>
  <?php include "../public/footer.php"; ?>

  <!-- JavaScript for Password Toggle -->
  <script>
    document.querySelector('.toggle-password').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.textContent = type === 'password' ? '👁️' : '🙈';
    });
  </script>
</body>
</html>
