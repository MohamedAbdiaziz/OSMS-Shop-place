<?php
include_once('../db/session.php');
require_once("../db/DbConnect.php");
require_once 'send_email.php'; // Email sending function

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];
$token = bin2hex(random_bytes(50)); // Generate a unique token

if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: ../pages/register.php");
    exit();
}

$hashedPassword = md5($password);

try {
    $db = new DbConnect();
    $dbConn = $db->connect();

    $sql = "INSERT INTO tblcustomer (Name, Username, Email, Password, Mobile, Address, Status,Token) VALUES (:name, :username, :email, :password, :mobile, :address,'Inactive',:token)";
    $stmt = $dbConn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':token', $token);

    if ($stmt->execute()) {
        $confirmation_link = "http://localhost:8082/osm/pages/confirm.php?token=$token";
        $subject = "Email Confirmation";
        $message = "Please click the link to confirm your email: $confirmation_link";
        send_email($email, $subject, $message);
        // $_SESSION['success'] = "Registration successful. Please log in.";
        header("Location: ../pages/login.php");
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../pages/login.php");
    }
} catch (PDOException $e) {
    $errorMsg = $e->getMessage();
    // Extract the relevant part of the error message
    if (strpos($errorMsg, 'Duplicate entry') !== false) {
        $start = strpos($errorMsg, 'Duplicate entry');
        $errorMessage = substr($errorMsg, $start);
    } else {
        $errorMessage = "An error occurred. Please try again.";
    }
    $_SESSION['error'] = $errorMessage;
    header("Location: ../pages/login.php");
    exit();
}
?>
