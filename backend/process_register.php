<?php
include_once('../db/session.php');
require_once("../db/DbConnect.php");

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];

if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: ../pages/register.php");
    exit();
}

$hashedPassword = md5($password);

try {
    $db = new DbConnect();
    $dbConn = $db->connect();

    $sql = "INSERT INTO tblcustomer (Name, Username, Email, Password, Mobile, Address, Status) VALUES (:name, :username, :email, :password, :mobile, :address,'Active')";
    $stmt = $dbConn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':address', $address);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please log in.";
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
