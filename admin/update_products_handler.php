<?php
include "../public/db.php"; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $category_id = $conn->real_escape_string($_POST['category_id']);

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', quantity='$quantity', category_id='$category_id' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product updated successfully!'); window.location = 'admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>