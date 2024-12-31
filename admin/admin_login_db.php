<?php
session_start();
include "../public/db.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists
    $query = "SELECT id, name, email, password FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $db_email, $db_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            // Store admin information in session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $db_email;
            $_SESSION['admin_logged_in'] = true;

            // Redirect to the admin dashboard
            header("Location: admin_profile.php"); // Replace with your actual dashboard URL
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password!'); window.location.href='admin_login.php';</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Email not found!'); window.location.href='admin_login.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method!'); window.location.href='admin_login.php';</script>";
}
?>
