<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}

require_once "db_connect.php";

$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($userId === null) {
    die("Error: Invalid user ID.");
}

$stmt = $conn->prepare("SELECT username, is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Error: User not found.");
}

$stmt->bind_result($username, $isAdmin);
$stmt->fetch();
$stmt->close();

$username = htmlspecialchars($username);

if ($isAdmin == 2) {
    header("Location: banned.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KLIX Profile</title>
<style>
body {
    background: #202020;
    color: white;
    font-family: Arial, sans-serif;
    text-align: center;
}
.profile-info {
    background: #272727;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    margin: 100px auto;
}
</style>
</head>
<body>

<div class="profile-info">
    <h2>Profile Information</h2>
    <p><strong>Username:</strong> <?php echo $username; ?></p>

    <?php if ($isAdmin == 1): ?>
        <p><strong>Role:</strong> Administrator</p>
    <?php endif; ?>
</div>

</body>
</html>
