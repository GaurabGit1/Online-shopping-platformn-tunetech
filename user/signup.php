<?php 
session_start();
include "../public/header.php";
?>
<body>
  <main class="signup-section">
    <div class="signup-container">
      <!-- Left Placeholder -->
      <div class="signup-image">
        <div><img src="../uploads/1.png" alt="Signup Placeholder"></div>
      </div>

      <!-- Signup Form -->
      <div class="signup-form">
        <h2>Welcome</h2>
        <p>Sign Up</p>
        <form action="signup_db.php" method="POST" id="signup_form">
          <div class="form-group">
            <input type="text" name="name" id="name" placeholder="Enter your name here" required>
            <p id="name-description" style="color: red; display: none;">Name must be longer than 2 characters.</p>
          </div>
          <div class="form-group">
            <input type="email" name="email" id="email" placeholder="Enter your e-mail address here" required>
            <p id="email-description" style="color: red; display: none;">Please enter a valid email address.</p>
          </div>
          <div class="form-group password-field">
            <input type="password" name="password" id="password" placeholder="Enter a strong password" required>
            <button type="button" class="toggle-password">👁️</button>
            <p id="password-description" style="color: red; display: none;">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</p>
          </div>
          <div class="form-group password-field">
            <input type="password" name="confirm_password" id="confirm-password" placeholder="Re-enter the password to confirm" required>
            <p id="password-match-message" style="color: red; display: none;">Passwords do not match.</p>
          </div>
          <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('signup_form');
      const nameInput = form.name;
      const emailInput = form.email;
      const passwordInput = form.password;
      const confirmPasswordInput = form.confirm_password;
      const nameDescription = document.getElementById('name-description');
      const emailDescription = document.getElementById('email-description');
      const passwordDescription = document.getElementById('password-description');
      const passwordMatchMessage = document.getElementById('password-match-message');
      const togglePasswordButton = document.querySelector('.toggle-password');

      form.addEventListener('submit', function (event) {
        const name = nameInput.value.trim();
        const email = emailInput.value;
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        let valid = true;

        // Name validation
        if (name.length <= 2) {
          nameDescription.style.display = 'block';
          valid = false;
        } else {
          nameDescription.style.display = 'none';
        }

        // Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
          emailDescription.style.display = 'block';
          valid = false;
        } else {
          emailDescription.style.display = 'none';
        }

        // Password validation
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(password)) {
          passwordDescription.style.display = 'block';
          valid = false;
        } else {
          passwordDescription.style.display = 'none';
        }

        // Confirm password validation
        if (password !== confirmPassword) {
          passwordMatchMessage.style.display = 'block';
          valid = false;
        } else {
          passwordMatchMessage.style.display = 'none';
        }

        if (!valid) {
          event.preventDefault();
        }
      });

      // Toggle password visibility
      togglePasswordButton.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        confirmPasswordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
      });

      // Real-time validation
      nameInput.addEventListener('input', function () {
        if (nameInput.value.trim().length <= 3) {
          nameDescription.style.display = 'block';
        } else {
          nameDescription.style.display = 'none';
        }
      });

      emailInput.addEventListener('input', function () {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
          emailDescription.style.display = 'block';
        } else {
          emailDescription.style.display = 'none';
        }
      });

      passwordInput.addEventListener('input', function () {
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(passwordInput.value)) {
          passwordDescription.style.display = 'block';
        } else {
          passwordDescription.style.display = 'none';
        }
      });

      confirmPasswordInput.addEventListener('input', function () {
        if (passwordInput.value !== confirmPasswordInput.value) {
          passwordMatchMessage.style.display = 'block';
        } else {
          passwordMatchMessage.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
