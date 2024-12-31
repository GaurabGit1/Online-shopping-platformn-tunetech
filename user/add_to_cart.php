<?php
// Ensure user is logged in before adding to cart
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

include "../public/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    // Set quantity to 1 if not provided
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Check if quantity is greater than 0
    if ($quantity <= 0) {
        echo "<script>alert('Please select a valid quantity!'); window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit;
    }

    // Check if product is already in the cart
    $sql = "SELECT id FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update quantity if product is already in the cart
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $update_stmt->execute();
    } else {
        // Insert new entry if product is not in the cart
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    // Redirect to the previous page (cart page or product page)
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
