<?php
$dsn = 'mysql:host=localhost;dbname=bccemseg_token_db1';
$username = 'bccemseg_enrico';
$password = '09214014Enrico@21';



try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>
