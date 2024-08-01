<?php
require 'db_connection.php'; // Include this line to establish database connection

// Token expiration time (e.g., 24 hours)
$token_expiration_time = 24 * 60 * 60;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Retrieve user ID and token creation time from user_registration table using the token
    $sql = "SELECT user_id, created_at FROM user_registration WHERE token = :token AND type = 'registration'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user_id = $row['user_id'];
        $token_created_at = strtotime($row['created_at']);
        $current_time = time();

        // Check if the token is expired
        if (($current_time - $token_created_at) < $token_expiration_time) {
            // Update user's status to active and email_confirmed in the users table
            $sql_update_user = "UPDATE users SET status = 'active', email_confirmed = 1 WHERE id = :user_id";
            $stmt_update_user = $conn->prepare($sql_update_user);
            $stmt_update_user->bindParam(':user_id', $user_id);

            if ($stmt_update_user->execute()) {
                // Delete the registration token from user_registration table
                $sql_delete_token = "DELETE FROM user_registration WHERE user_id = :user_id AND type = 'registration'";
                $stmt_delete_token = $conn->prepare($sql_delete_token);
                $stmt_delete_token->bindParam(':user_id', $user_id);
                $stmt_delete_token->execute();

                // Display message and redirect to login page
                echo "<html>
                        <head>
                            <meta charset='UTF-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                            <title>Email Confirmation</title>
                            <script>
                                setTimeout(function() {
                                    window.location.href = 'login.php';
                                }, 3000); // 3 seconds delay
                            </script>
                        </head>
                        <body>
                            <h2>Your account is verified. Proceeding to login...</h2>
                        </body>
                      </html>";
                exit();
            } else {
                echo "Error activating user account: " . $stmt_update_user->errorInfo()[2];
            }
        } else {
            echo "Token expired.";
        }
    } else {
        echo "Invalid token.";
    }
} else {
    echo "Token not provided.";
}
?>
