<?php
include('connect.php');

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $reason = trim($_POST['reason']);

    if (empty($username) || empty($password) || empty($reason)) {
        $error = "All fields are required.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO applications (username, password, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $reason);

        if ($stmt->execute()) {
            $success = "Application submitted successfully.";
        } else {
            $error = "Username already exists or database error.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Klix - Apply</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="background"></div>

<div class="container">
    <h1>KLIX</h1>
    <p class="subtitle">Apply for access</p>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <textarea name="reason" placeholder="Why should we accept you?" required></textarea>
        </div>

        <button type="submit">Submit Application</button>
    </form>
</div>

</body>
</html>
