<?php
session_start();
require 'db_connection.php'; // Adjust this according to your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $lastname = $_POST['lastname'] ?? '';

    // Check if the email exists in the users table
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // If email not found, insert into users table
        $sql = "INSERT INTO users (email, firstname, middlename, lastname) 
                VALUES (:email, :firstname, :middlename, :lastname)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->execute();

        $user_id = $conn->lastInsertId();
    } else {
        $user_id = $row['id'];
    }

    // Insert into enrollment table
    $sql = "INSERT INTO enrollment (user_id, status) 
            VALUES (:user_id, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $enrollment_id = $conn->lastInsertId(); // Retrieve last inserted ID

    // Display confirmation message
    echo "Enrollment Confirmation<br>";
    echo "Your enrollment has been successfully submitted.<br>";
    echo "Enrollment ID: $enrollment_id";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Form</title>
</head>
<body>
    <h2>Student Enrollment Form</h2>
    <form id="enrollmentForm" class="row" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
        <div class="">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Please provide an email.</div>
        </div>

        <div class="">
            <label for="firstname">First name</label>
            <input type="text" class="form-control" id="firstname" name="firstname" required>
            <div class="invalid-feedback">Please provide a first name.</div>
        </div>

        <div class="">
            <label for="middlename">Middle name (optional)</label>
            <input type="text" class="form-control" id="middlename" name="middlename">
            <div class="invalid-feedback">Please provide a middle name.</div>
        </div>

        <div class="">
            <label for="lastname">Last name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" required>
            <div class="invalid-feedback">Please provide a last name.</div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>

    
</body>
</html>
