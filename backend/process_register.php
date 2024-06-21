<?php
session_start();
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

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $db = new DbConnect();
    $dbConn = $db->connect();

    $sql = "INSERT INTO tblcustomer (Name, Username, Email, Password, Mobile, Address) VALUES (:name, :username, :email, :password, :mobile, :address)";
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
        header("Location: ../pages/register.php");
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: ../pages/register.php");
}
?>
