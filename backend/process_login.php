<?php
session_start();
require_once("../db/DbConnect.php");

$email = $_POST['email'];
$password = $_POST['password'];

try {
    $db = new DbConnect();
    $dbConn = $db->connect();

    $sql = "SELECT * FROM tblcustomer WHERE Email = :email";
    $stmt = $dbConn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['customer_id'] = $user['ID'];
        $_SESSION['customer_name'] = $user['Name'];
        header("Location: ../pages/account.php");
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../pages/login.php");
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: ../pages/login.php");
}
?>
