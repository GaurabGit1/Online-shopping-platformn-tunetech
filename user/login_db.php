<?php
session_start();
include "../public/db.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists
    $query = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $db_email, $db_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            // Store user information in session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $db_email;

            // Redirect to the homepage or dashboard
            header("Location: ../public/products.php"); // Replace with your actual homepage URL
            exit;
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Email not found!'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
