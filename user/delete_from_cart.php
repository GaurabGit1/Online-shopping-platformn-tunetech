<?php
session_start();
include "../public/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $sql = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Item removed from cart.'); </script>";
    } else {
        echo "<script>alert('Failed to remove item.'); </script>";
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);

    $stmt->close();
}
$conn->close();
?>
