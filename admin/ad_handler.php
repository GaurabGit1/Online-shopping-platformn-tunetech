<?php
// Database connection
include "../public/db.php";

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE id = $deleteId";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product deleted successfully!');</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");

?>