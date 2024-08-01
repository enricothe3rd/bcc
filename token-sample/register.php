<?php
require 'db_connection.php'; // Include this line to establish a database connection

// Include PHPMailer files and other necessary dependencies
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = ""; // Initialize message variable

// Handle form submission and database operations below
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // Get the selected role from the form

    // Check if the email already exists
    $sql_check_email = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->fetchColumn() > 0) {
        $msg = "This email is already registered.";
    } else {
        // Generate a random registration token
        $registration_token = bin2hex(random_bytes(20)); // Generate a 40-character random string

        // Insert new user record
        $sql_insert_user = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);

        if ($stmt_insert_user) {
            $stmt_insert_user->bindParam(':email', $email);
            $stmt_insert_user->bindParam(':password', $password);
            $stmt_insert_user->bindParam(':role', $role);

            if ($stmt_insert_user->execute()) {
                $user_id = $conn->lastInsertId();

                // Insert registration token into user_registration table
                $sql_insert_token = "INSERT INTO user_registration (user_id, token, type) VALUES (:user_id, :token, 'registration')";
                $stmt_insert_token = $conn->prepare($sql_insert_token);

                if ($stmt_insert_token) {
                    $stmt_insert_token->bindParam(':user_id', $user_id);
                    $stmt_insert_token->bindParam(':token', $registration_token);

                    if ($stmt_insert_token->execute()) {
                        // Send registration email
                        $mail = new PHPMailer();
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = 'pedrajetajr22@gmail.com'; // SMTP username
                        $mail->Password = 'mtesveduhyxvlfxa'; // SMTP password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('your-email@example.com', 'Your Name');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Welcome! Confirm Your Email';
                        $mail->Body    = 'Welcome to our platform! Click the following link to confirm your email and set your password: <a href="http://localhost/enrollment/token-sample/confirm_email1.php?token=' . $registration_token . '">Confirm Email</a>';

                        if (!$mail->send()) {
                            $msg = "Error sending email: " . $mail->ErrorInfo;
                        } else {
                            $msg = "Registration successful! A confirmation email has been sent to your email address.";
                            // Redirect to the login page after a successful registration
                            header("Location: index.php");
                            exit();
                        }
                    } else {
                        $msg = "Error inserting registration token: " . $stmt_insert_token->errorInfo()[2];
                    }
                    $stmt_insert_token->closeCursor();
                } else {
                    $msg = "Error preparing registration token statement: " . $conn->errorInfo()[2];
                }
            } else {
                $msg = "Error registering user: " . $stmt_insert_user->errorInfo()[2];
            }
            $stmt_insert_user->closeCursor();
        } else {
            $msg = "Error preparing user registration statement: " . $conn->errorInfo()[2];
        }
    }
    $stmt_check_email->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="./output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="h-full">

<div class="flex min-h-screen flex-col justify-center max-w-md mx-auto md:max-w-1xl">
    <form id="registerForm" class="space-y-6" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return handleFormSubmit();">
     
        <h4 class="font-body text-[1.8rem] text-custom-red font-semibold">Register</h4>    
        <h5 class="font-body text-[1.2rem] text-custom-red font-medium tracking-wider">
            Doesn't have an account yet? 
            <a href="index.php" class="inline-block underline text-custom-darkRed font-bold">Sign Up</a>
        </h5>

        <div>
            <label for="email" class="block font-body text-[1.2rem] font-semibold leading-6 text-custom-black">Email Address</label>
            <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" style="outline:none;"
                class="block w-full rounded-md border-0 p-[1rem] text-gray-900 shadow-md ring-2 ring-inset ring-gray-300 placeholder:text-gray-300">          
                <div id="emailError" class="text-red-600 text-sm mt-1"></div>
            </div>
        </div>
        
        <div>
            <div class="flex justify-between items-center">
                <label for="password" class="font-body text-[1.2rem] font-semibold leading-6 text-custom-black">Password</label>
                <a href="your-link-here" class="underline text-custom-darkRed font-bold">Forgot Password?</a>
            </div>
            <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter 6 characters or more" style="outline:none;"
                class="block w-full rounded-md border-0 p-[1rem] text-gray-900 shadow-md ring-2 ring-inset ring-gray-300 placeholder:text-gray-300">
                <div id="passwordError" class="text-red-600 text-sm mt-1"></div>
            </div>
        </div>

        <label for="role">Role:</label><br>
        <input type="radio" id="student" name="role" value="student" checked>
        <label for="student">Student</label><br>
        <input type="radio" id="teacher" name="role" value="teacher">
        <label for="teacher">Teacher</label><br>
        <input type="radio" id="admin" name="role" value="admin">
        <label for="admin">Admin</label><br><br>
     
     
        <?php if ($msg): ?>
    <p id="message"><?php echo htmlspecialchars($msg); ?></p>
    <script>
        // Function to hide the message after 5 seconds (5000 milliseconds)
        setTimeout(function() {
            var messageElement = document.getElementById('message');
            if (messageElement) {
                messageElement.style.display = 'none';
            }
        }, 5000); // Adjust the time as needed
    </script>
<?php endif; ?>

        <button type="submit" class="flex w-full justify-center rounded-md bg-custom-red px-3 py-5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span>Register</span>
        </button>
  
    

    </form>
</div>


<!-- Spinner Element -->
<div id="loadingSpinner" class="hidden flex items-center justify-center fixed inset-0 bg-black bg-opacity-50 z-50">
    <div
        class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] dark:text-white"
        role="status">
        <span
            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
            >Loading...</span>
    </div>
</div>

<script>
    function handleFormSubmit() {
        console.log("Form submit triggered");
        showSpinner();

        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var isValid = true;

        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';

        if (email.trim() === '') {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        }
        if (password.trim().length < 6) {
            document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long.';
            isValid = false;
        }

        if (!isValid) {
            hideSpinner();
            // Automatically remove error messages after 5 seconds
            setTimeout(function() {
                document.getElementById('emailError').textContent = '';
                document.getElementById('passwordError').textContent = '';
            }, 5000);

            return false;
        }

        return true;
    }

    function showSpinner() {
        var spinner = document.getElementById('loadingSpinner');
        spinner.classList.remove('hidden');
    }

    function hideSpinner() {
        var spinner = document.getElementById('loadingSpinner');
        spinner.classList.add('hidden');
    }
</script>

</body>
</html>

<!-- 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    status VARCHAR(50) DEFAULT 'inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    email_confirmed TINYINT(1) NOT NULL DEFAULT 0
);


CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL
);


CREATE TABLE user_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
); -->