<?php
include('../public/db.php');

// Start session to check for logged-in user

$user_logged_in = false;
$user_profile_picture = '';

if (isset($_SESSION['user_id'])) {
    $user_logged_in = true;
    $user_id = $_SESSION['user_id'];

    // Query to fetch user profile picture
    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_picture);
    $stmt->fetch();
    $stmt->close();

    $user_profile_picture = $profile_picture ? $profile_picture : '../uploads/profile_pictures/default-profile.jpg'; // Fallback to default image
}
?>