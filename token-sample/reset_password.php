<?php
require 'db_connection.php'; // Include this line to establish a database connection

// Initialize variables
$msg = '';
$token = isset($_GET['token']) ? $_GET['token'] : null; // Get the token from the URL parameter if it exists

// Check if token is provided and not empty
if (empty($token)) {
    // Redirect to an error page or show a user-friendly message
    header("Location: forgot_password.php");
    exit;
} else {
    // Check if the token exists in the password_resets table and is valid
    $sql_check_token = "SELECT id, email, created_at FROM password_resets WHERE token = :token AND created_at >= NOW() - INTERVAL 1 DAY";
    $stmt_check_token = $conn->prepare($sql_check_token);
    $stmt_check_token->bindParam(':token', $token);
    $stmt_check_token->execute();

    if ($stmt_check_token->rowCount() > 0) {
        $row = $stmt_check_token->fetch(PDO::FETCH_ASSOC);
        $email = $row['email'];

        // Display the password reset form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate password and update in users table
            if (!empty($new_password) && $new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password in the users table
                $sql_update_password = "UPDATE users SET password = :password WHERE email = :email";
                $stmt_update_password = $conn->prepare($sql_update_password);
                $stmt_update_password->bindParam(':password', $hashed_password);
                $stmt_update_password->bindParam(':email', $email);

                if ($stmt_update_password->execute()) {
                    // Delete the used token from password_resets table
                    $sql_delete_token = "DELETE FROM password_resets WHERE token = :token";
                    $stmt_delete_token = $conn->prepare($sql_delete_token);
                    $stmt_delete_token->bindParam(':token', $token);
                    $stmt_delete_token->execute();

                    $msg = "Password has been reset successfully.";
                } else {
                    $msg = "Error updating password.";
                }
            } else {
                $msg = "Passwords do not match or are empty.";
            }
        }
    } else {
        $msg = "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Password Reset</h2>
    <?php if (!empty($msg)): ?>
        <p><?php echo htmlspecialchars($msg); ?></p>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . urlencode(htmlspecialchars($token)); ?>" method="post">
            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password" required><br><br>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</body>
</html>
