<?php
include('connect.php');

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'accept') {

        $stmt = $conn->prepare("SELECT username, password FROM applications WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($username, $password);
        $stmt->fetch();
        $stmt->close();

        $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insert->bind_param("ss", $username, $password);
        $insert->execute();
        $insert->close();

        $update = $conn->prepare("UPDATE applications SET status='accepted' WHERE id=?");
        $update->bind_param("i", $id);
        $update->execute();
        $update->close();

    } elseif ($action === 'decline') {

        $update = $conn->prepare("UPDATE applications SET status='declined' WHERE id=?");
        $update->bind_param("i", $id);
        $update->execute();
        $update->close();
    }

    header("Location: admin.php");
    exit();
}

$result = $conn->query("SELECT * FROM applications WHERE status='pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Klix Admin</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container admin-panel">
    <h1>Pending Applications</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td>
                        <a class="action-btn" href="?action=accept&id=<?php echo $row['id']; ?>">Accept</a>
                        <a class="action-btn decline" href="?action=decline&id=<?php echo $row['id']; ?>">Decline</a>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    <?php else: ?>
        <p>No pending applications.</p>
    <?php endif; ?>
</div>

</body>
</html>
