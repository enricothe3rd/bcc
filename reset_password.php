<?php
require 'db_connection.php';

$error_message = '';

function validate_password($password) {
    // Ensure the password has at least one letter, one number, and one special character
    if (preg_match('/[A-Za-z]/', $password) &&
        preg_match('/[0-9]/', $password) &&
        preg_match('/[\W_]/', $password) &&
        strlen($password) >= 8) {
        return true;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql_check_token = "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()";
    $stmt_check_token = $conn->prepare($sql_check_token);
    $stmt_check_token->bindParam(':token', $token);
    $stmt_check_token->execute();

    if ($stmt_check_token->rowCount() > 0) {
        $reset_data = $stmt_check_token->fetch(PDO::FETCH_ASSOC);
        $email = $reset_data['email'];
        $expires_at = $reset_data['expires_at'];
        echo "Token valid. Expires at: " . $expires_at;
    } else {
        die('Invalid or expired token.');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validate the new password
    if (!validate_password($password)) {
        $error_message = 'Password must be at least 8 characters long, and include a letter, a number, and a special character.';
    } elseif ($password !== $password_confirm) {
        $error_message = 'Passwords do not match.';
    } else {
        $new_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the token is valid and not expired
        $sql_check_token = "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()";
        $stmt_check_token = $conn->prepare($sql_check_token);
        $stmt_check_token->bindParam(':token', $token);
        $stmt_check_token->execute();

        if ($stmt_check_token->rowCount() > 0) {
            $reset_data = $stmt_check_token->fetch(PDO::FETCH_ASSOC);
            $email = $reset_data['email'];

            // Update the user's password
            $sql_update_password = "UPDATE users SET password = :password WHERE email = :email";
            $stmt_update_password = $conn->prepare($sql_update_password);
            $stmt_update_password->bindParam(':password', $new_password);
            $stmt_update_password->bindParam(':email', $email);

            if ($stmt_update_password->execute()) {
                // Delete the reset token
                $sql_delete_token = "DELETE FROM password_resets WHERE token = :token";
                $stmt_delete_token = $conn->prepare($sql_delete_token);
                $stmt_delete_token->bindParam(':token', $token);
                $stmt_delete_token->execute();

                echo "Password reset successful!";
            } else {
                $error_message = "Error updating password.";
            }
        } else {
            $error_message = "Invalid or expired token.";
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="./output.css" rel="stylesheet">
    <style>
        /* Modern styling for the error and status messages */
        .message {
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding around text */
            margin-top: 1rem; /* Margin at the top */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow */
            opacity: 1;
            transition: opacity 0.5s ease-in-out; /* Fade-out effect */
        }

        .message-success {
            background-color: #D1FAE5; /* Light green background */
            color: #065F46; /* Darker green text */
            border: 1px solid #A7F3D0; /* Light green border */
        }

        .message-error {
            background-color: #FEE2E2; /* Light red background */
            color: #B91C1C; /* Darker red text */
            border: 1px solid #FECACA; /* Light red border */
        }

        /* Modern email input styling */
        .email-input {
            background-color: #FFFFFF; /* White background */
            border: 1px solid #D1D5DB; /* Light gray border */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding inside the input */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Soft shadow */
            width: 100%; /* Full width */
            transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transition */
        }

        .email-input:focus {
            border-color: #F43F5E; /* Accent color for focus */
            box-shadow: 0 0 0 1px #F43F5E; /* Accent color shadow */
            outline: none; /* Remove default outline */
        }

        .email-input::placeholder {
            color: #9CA3AF; /* Light gray placeholder text */
            opacity: 1; /* Ensure placeholder text is visible */
        }
    </style>
</head>
<body class="h-full">
<div class="flex min-h-screen flex-col justify-center max-w-md mx-auto md:max-w-1xl">
    <form id="forgotPasswordForm" class="space-y-6" action="forgot_password.php" method="post">
        <h4 class="font-body text-[1.8rem] text-custom-red font-semibold">Forgot Password</h4>
        <div>
            <label for="email" class="block font-body text-[1.2rem] font-semibold leading-6 text-custom-black">Email Address</label>
            <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" 
                       class="email-input">          
                <div id="emailError" class="text-red-600 text-sm mt-1"></div>
            </div>
        </div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-custom-red px-3 py-5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600">
            <span>Send Reset Link</span>
        </button>
        <?php if ($msg): ?>
            <p id="status
