<?php session_start(); 
include "../public/header.php";
?>
<body>
  <main class="signup-section">
    <div class="signup-container">
      <!-- Left Placeholder -->
      <div class="signup-image">
        <div><img src="../uploads/1.png" alt="Login Placeholder"></div>
      </div>

      <!-- Login Form -->
      <div class="signup-form">
        <h2>Welcome</h2>
        <p>Login</p>
        <form action="./login_db.php" method="POST" name="login_form" id="login_form">
          <div class="form-group">
            <input type="email" name="email" id="email" placeholder="Enter your e-mail address here" required>
            <p id="email-description" style="color: red; display: none;">Please enter a valid email address.</p>
          </div>
          <div class="form-group password-field">
            <input type="password" name="password" id="password" placeholder="Enter your password here" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <button type="submit" class="signup-btn">Log In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
      </div>
    </div>
  </main>
  <?php include "../public/footer.php"; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('login_form');
      const emailInput = form.email;
      const emailDescription = document.getElementById('email-description');
      const passwordInput = form.password;
      const togglePasswordButton = document.querySelector('.toggle-password');

      form.addEventListener('submit', function (event) {
        const email = emailInput.value;

        // Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
          emailDescription.style.display = 'block';
          event.preventDefault();
        } else {
          emailDescription.style.display = 'none';
        }
      });

      // Toggle password visibility
      togglePasswordButton.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
      });

      // Real-time email validation
      emailInput.addEventListener('input', function () {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
          emailDescription.style.display = 'block';
        } else {
          emailDescription.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
