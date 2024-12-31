<?php
include "../public/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $category_id = $conn->real_escape_string($_POST['category_id']);

    // Handle file upload
    $image = $_FILES['image'];
    $imagePath = '../uploads/' . basename($image['name']);
    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        $imageUrl = $conn->real_escape_string($imagePath);

        // Insert into database
        $sql = "INSERT INTO products (name, description, price, quantity, imageurl, category_id) 
                VALUES ('$name', '$description', '$price', '$quantity', '$imageUrl', '$category_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>
