<?php
require 'db_connection.php'; // Include this line to establish a database connection

// Include PHPMailer files and other necessary dependencies
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = ''; // Initialize the message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $sql_check_email = "SELECT id FROM users WHERE email = :email";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        // Generate a random reset token
        $reset_token = bin2hex(random_bytes(20));

        // Calculate expiration time (e.g., 30 seconds from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 seconds'));

        // Insert reset token into the password_resets table with expiration time
        $sql_insert_token = "INSERT INTO password_resets (email, token, created_at, expires_at) VALUES (:email, :token, NOW(), :expires_at)";
        $stmt_insert_token = $conn->prepare($sql_insert_token);
        $stmt_insert_token->bindParam(':email', $email);
        $stmt_insert_token->bindParam(':token', $reset_token);
        $stmt_insert_token->bindParam(':expires_at', $expires_at);

        if ($stmt_insert_token->execute()) {
            // Send password reset email
            $mail = new PHPMailer(true); // Passing true enables exceptions
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'pedrajetajr22@gmail.com'; // SMTP username
                $mail->Password = 'mtesveduhyxvlfxa'; // SMTP password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email content
                $mail->setFrom('your-email@example.com', 'Your Name');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = 'Click the following link to reset your password: <a href="http://localhost/enrollment/token-sample/reset_password.php?token=' . $reset_token . '">Reset Password</a>';

                $mail->send();
                $msg = "Password reset instructions have been sent to your email.";

                // Cleanup expired tokens from password_resets table
                $now = date('Y-m-d H:i:s');
                $sql_delete_expired = "DELETE FROM password_resets WHERE expires_at <= :now";
                $stmt_delete_expired = $conn->prepare($sql_delete_expired);
                $stmt_delete_expired->bindParam(':now', $now);
                $stmt_delete_expired->execute();
                
            } catch (Exception $e) {
                $msg = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $msg = "Error inserting reset token: " . $stmt_insert_token->errorInfo()[2];
        }
    } else {
        $msg = "No user found with that email address.";
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
    <?php if (!empty($msg)) : ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Send Password Reset Email</button>
    </form>
</body>
</html>
