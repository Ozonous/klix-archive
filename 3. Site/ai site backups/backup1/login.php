<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($username)) {
        die("Error: Username cannot be empty.");
    }

    if (empty($password)) {
        die("Error: Password cannot be empty.");
    }

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $id;
            echo "Login successful!";
        } else {
            echo "Error: Incorrect password.";
        }
    } else {
        echo "Error: Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
