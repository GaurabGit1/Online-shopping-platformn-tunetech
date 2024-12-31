<?php
session_start();
include "../public/db.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Validate password match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='admin_signup.php';</script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT id FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email is already taken!'); window.location.href='admin_signup.php';</script>";
        exit;
    }

    // Insert new admin into database
    $insert_query = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($insert_stmt->execute()) {
        // Admin signup successful
        echo "<script>alert('Admin account created successfully!'); window.location.href='admin_login.php';</script>";
    } else {
        echo "<script>alert('Error creating admin account. Please try again.'); window.location.href='admin_signup.php';</script>";
    }

    $stmt->close();
    $insert_stmt->close();
    $conn->close();
}
?>
