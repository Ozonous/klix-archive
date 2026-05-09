<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (strlen($username) < 3 || strlen($username) > 15) {
        die("Error: Username must be between 3 and 15 characters.");
    }

    if (empty($password)) {
        die("Error: Password cannot be empty.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $passwordHash);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: Username already taken.";
    }

    $stmt->close();
    $conn->close();
}
?>
