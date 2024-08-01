<?php
session_start();
require 'db_connection.php'; // Include this line to establish a database connection

// Check if user is not logged in or if their role is not 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page or display an error message
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}


// Handle remove action if user ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_user'])) {
    $user_id = $_POST['remove_user'];

    // Delete user's registration tokens first (if any)
    $sql_delete_tokens = "DELETE FROM user_registration WHERE user_id = :user_id";
    $stmt_delete_tokens = $conn->prepare($sql_delete_tokens);
    $stmt_delete_tokens->bindParam(':user_id', $user_id);

    if ($stmt_delete_tokens->execute()) {
        // Now delete the user from the users table
        $sql_delete_user = "DELETE FROM users WHERE id = :user_id";
        $stmt_delete_user = $conn->prepare($sql_delete_user);
        $stmt_delete_user->bindParam(':user_id', $user_id);

        if ($stmt_delete_user->execute()) {
            // Set success message
            $msg = "User removed successfully.";

            // Redirect to prevent re-submission on refresh
            header("Location: ".$_SERVER['PHP_SELF']);
            exit(); // Ensure script stops execution after redirection
        } else {
            $msg = "Error removing user: " . $stmt_delete_user->errorInfo()[2];
        }
    } else {
        $msg = "Error removing user's registration tokens: " . $stmt_delete_tokens->errorInfo()[2];
    }
}

// Fetch all users from the database
$sql_select_users = "SELECT id, email, role, status, created_at FROM users";
$stmt_select_users = $conn->query($sql_select_users);

$users = [];
if ($stmt_select_users) {
    $users = $stmt_select_users->fetchAll(PDO::FETCH_ASSOC);
} else {
    $error = "Error fetching users: " . $conn->errorInfo()[2];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show All Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>All Users</h2>
    <?php if (isset($msg)): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p>Error: <?php echo $error; ?></p>
    <?php else: ?>
        <?php if (count($users) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td><?php echo $user['status']; ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="remove_user" value="<?php echo $user['id']; ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</body>
</html>
