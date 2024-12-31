<?php session_start(); 
include "../admin/admin_header.php"; 
?>
<body>
  <main class="signup-section">
    <div class="signup-container">
      <!-- Left Placeholder -->
      <div class="signup-image">
        <div><img src="../uploads/1.png" alt="Admin Signup Placeholder"></div>
      </div>

      <!-- Admin Signup Form -->
      <div class="signup-form">
        <h2>Admin Signup</h2>
        <form action="admin_signup_db.php" method="POST" name="admin_signup_form">
          <div class="form-group">
            <input type="text" name="name" placeholder="Enter your name here" required>
          </div>
          <div class="form-group">
            <input type="email" name="email" placeholder="Enter your e-mail address here" required>
          </div>
          <div class="form-group password-field">
            <input type="password" name="password" placeholder="Enter a strong password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <div class="form-group password-field">
            <input type="password" name="confirm-password" placeholder="Re-enter the password to confirm" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <p>Already have an admin account? <a href="admin_login.php">Log In</a></p>
      </div>
    </div>
  </main>
  <?php include "../public/footer.php"; ?>

  <script>
    // JavaScript for password toggle
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
      button.addEventListener('click', () => {
        const passwordInput = button.previousElementSibling;
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        button.textContent = type === 'password' ? '👁️' : '🙈';
      });
    });
  </script>
</body>
