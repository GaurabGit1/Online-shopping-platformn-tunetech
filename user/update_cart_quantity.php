<?php
// Start the session
session_start();

// Include necessary files
include "../public/db.php"; // Include database connection
include "../public/loggedin_session.php"; // Include session logic

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'] ?? null;
    $new_quantity = $_POST['quantity'] ?? null;

    // Validate input
    if (!$cart_id || !$new_quantity || !$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit();
    }

    // Validate the new quantity
    if ($new_quantity <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Quantity must be greater than zero.']);
        exit();
    }

    // Update the cart quantity
    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);

    if ($update_stmt->execute()) {
        echo json_encode(['success' => 'Quantity updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update quantity.']);
    }

    $update_stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
