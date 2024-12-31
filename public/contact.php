<?php
session_start();
include "../public/header.php";
?>

<main>
    <div class="contact-container">
        <h2>Contact Us</h2>
        <?php
        // Display success or error messages if passed via GET
        if (isset($_GET['status']) && $_GET['status'] == 'success') {
            echo '<p class="message">Your message has been sent successfully!</p>';
        } elseif (isset($_GET['status']) && $_GET['status'] == 'error') {
            echo '<p class="error">Failed to send your message. Please try again.</p>';
        }
        ?>
        <form id="contactForm" method="POST" action="contact_handler.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <p id="nameError" class="error-message"></p>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <p id="emailError" class="error-message"></p>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
</main>

<script>
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const nameError = document.getElementById('nameError');
    if (name.length <= 3) {
        nameError.textContent = 'Name must be more than 3 letters.';
    } else {
        nameError.textContent = '';
    }
});

document.getElementById('email').addEventListener('input', function() {
    const email = this.value;
    const emailError = document.getElementById('emailError');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailError.textContent = 'Please enter a valid email address.';
    } else {
        emailError.textContent = '';
    }
});

document.getElementById('contactForm').addEventListener('submit', function(event) {
    let valid = true;

    // Validate name
    const name = document.getElementById('name').value;
    const nameError = document.getElementById('nameError');
    if (name.length <= 3) {
        nameError.textContent = 'Name must be more than 3 letters.';
        valid = false;
    } else {
        nameError.textContent = '';
    }

    // Validate email
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailError.textContent = 'Please enter a valid email address.';
        valid = false;
    } else {
        emailError.textContent = '';
    }

    if (!valid) {
        event.preventDefault();
    }
});
</script>

<?php include "../public/footer.php"; ?>
