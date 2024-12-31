<?php
// Redirect to login if the user is not logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "../public/db.php";

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<?php include "../public/header.php"; ?>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>Welcome</h2>
            <ul>
                <li><a href="orders.php">View Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>User Profile</h1>
            <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>
    <?php include "../public/footer.php"; ?>

