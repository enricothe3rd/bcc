<?php
session_start();
require 'db_connection.php';
require 'session_timeout.php'; // Include session timeout mechanism

$msg = ''; // Initialize $msg to avoid undefined variable notice

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user details based on email
    $sql = "SELECT id, email, password, role, email_confirmed FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check if email is confirmed
            if ($user['email_confirmed'] == 0) {
                $msg = "Please confirm your email first.";
            } else {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start session and set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    // Redirect to dashboard or appropriate page based on role
                    if ($user['role'] == 'admin') {
                        header("Location: admin_dashboard.php");
                        exit();
                    } elseif ($user['role'] == 'college_department') {
                        header("Location: college_department_dashboard.php");
                        exit();
                    } elseif ($user['role'] == 'student') {
                        header("Location: student_dashboard_process.php");
                        exit();
                    }elseif ($user['role'] == 'registrar') {
                        header("Location: registrar_dashboard.php");
                        exit();
                    } else {
                        // Handle other roles or redirect to a default page
                        header("Location: default_dashboard.php");
                        exit();
                    }
                } else {
                    $msg = "Invalid email or password.";
                }
            }
        } else {
            $msg = "Invalid email or password.";
        }
    } else {
        $msg = "Error preparing statement: " . $conn->errorInfo()[2];
    }

    // Store the message in session and redirect to avoid form resubmission
    $_SESSION['msg'] = $msg;
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>


<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="./output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">

   
</head>
<body class="h-full">


    <div class="flex min-h-screen flex-col justify-center max-w-md mx-auto md:max-w-1xl">

<form id="sign-in-form" class="space-y-6" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return handleFormSubmit();">
    <h4 class="font-body text-[1.8rem] text-custom-red font-semibold">Login</h4>    
    <h5 class="font-body text-[1.2rem] text-custom-red font-medium tracking-wider">
        Doesn't have an account yet? 
        <a href="register.php" class="inline-block underline text-custom-darkRed font-bold">Sign Up</a>
    </h5>

    <div>
        <label for="email" class="block font-body text-[1.2rem] font-semibold leading-6 text-custom-black">Email Address</label>
        <div class="mt-2">
            <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" style="outline:none;"
            class="block w-full rounded-md border-0 p-[1rem] text-gray-900 shadow-md ring-2 ring-inset ring-gray-300 placeholder:text-gray-300 ">          
            <div id="emailError" class="text-red-600 text-sm mt-1"></div>
        </div>
    </div>
    
    <div>
        <div class="flex justify-between items-center">
            <label for="password" class="font-body text-[1.2rem] font-semibold leading-6 text-custom-black">Password</label>
            <a href="forgot_password.php" class="underline text-custom-darkRed font-bold">Forgot Password?</a>
        </div>
        <div class="mt-2">
            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter 6 characters or more" style="outline:none;"
            class="block w-full rounded-md border-0 p-[1rem] text-gray-900 shadow-md ring-2 ring-inset ring-gray-300 placeholder:text-gray-300">
            <div id="passwordError" class="text-red-600 text-sm mt-1"></div>
        </div>
    </div>

<!-- Modal structure -->
<div id="modal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <!-- Close button (X) -->
        <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <!-- SVG for "X" icon -->
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <!-- Modal message -->
        <p id="modal-message" class="text-center text-indigo-600"><?php echo $msg; ?></p>
    </div>
</div>





    <button type="submit"
            class="flex w-full justify-center rounded-md bg-custom-red px-3 py-5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        <span>Sign in</span>
    </button>

    <!-- Spinner -->
    <div id="spinner" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-white motion-reduce:animate-[spin_1.5s_linear_infinite]">
            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
        </div>
    </div>





<!-- //https://designmodo.com/login-forms-websites-apps/ -->

</form>

</div>
    </body>
    </html>

<script>
function showSpinner() {
    document.getElementById('spinner').classList.remove('hidden');

    // Keep the spinner visible for a longer time (e.g., 5 seconds)
    setTimeout(function() {
        document.getElementById('spinner').classList.add('hidden');
    }, 5000);
}

function hideSpinner() {
    document.getElementById('spinner').classList.add('hidden');
}

function handleFormSubmit() {
    console.log("Form submit triggered");
    showSpinner();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var isValid = true;

    document.getElementById('emailError').textContent = '';
    document.getElementById('passwordError').textContent = '';

    if (email.trim() === '') {
        document.getElementById('emailError').textContent = 'Please enter your email.';
        isValid = false;
        setTimeout(function() {
            document.getElementById('emailError').textContent = '';
        }, 3000);
    }

    if (password.trim() === '') {
        document.getElementById('passwordError').textContent = 'Please enter your password.';
        isValid = false;
        setTimeout(function() {
            document.getElementById('passwordError').textContent = '';
        }, 3000);
    }
    
    if (password.trim() === '') {
        document.getElementById('passwordError').textContent = 'Please enter your password.';
        isValid = false;
        setTimeout(function() {
            document.getElementById('passwordError').textContent = '';
        }, 3000);
    }
    
    

    if (!isValid) {
        hideSpinner();
    }

    console.log("Form valid: ", isValid);
    return isValid;
}

document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('modal');
      const closeModalButton = document.getElementById('close-modal');
      const emailError = document.getElementById('emailError');
      const passwordError = document.getElementById('passwordError');

      // Function to hide error messages
      const hideErrors = () => {
        emailError.style.display = 'none';
        passwordError.style.display = 'none';
      };

      // Function to show error messages
      const showErrors = () => {
        emailError.style.display = 'block'; // or 'inline' depending on your design
        passwordError.style.display = 'block'; // or 'inline'
      };

      // Check PHP variable to decide if modal should be shown
      const showModal = <?php echo isset($msg) && $msg !== '' ? 'true' : 'false'; ?>;
      if (showModal) {
        modal.classList.remove('hidden');
        hideErrors(); // Initially hide errors
      }

      closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
        location.reload(); // Reload the page when the modal is closed
      });

      // Optional: Close modal when clicking outside of it
      modal.addEventListener('click', (event) => {
        if (event.target === modal) {
          modal.classList.add('hidden');
        //   location.reload(); // Reload the page when clicking outside the modal
        }
      });
    });


</script>

