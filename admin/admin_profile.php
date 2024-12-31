<?php
session_start(); // Ensure the session is started

// Restrict access to admin profile unless logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include "../public/db.php";

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT name, email FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // If admin details are not found, redirect to login
    if (!$admin) {
        session_destroy();
        header("Location: admin_login.php");
        exit();
    }
} else {
    die("Error fetching admin details: " . $conn->error);
}

$stmt->close();
$conn->close();
?>

<?php include "../admin/admin_header.php"; ?>


<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="sales_page.php">Sales</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Welcome, Admin <?php echo htmlspecialchars($admin['name']); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($admin['email']); ?></p>
    </div>
</body>
</html>
