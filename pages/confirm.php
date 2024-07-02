<?php
include_once('../db/session.php');

require_once("../db/DbConnect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token and activate user
    $db = new DbConnect();
    $dbConn = $db->connect();
    $stmt = $dbConn->prepare("SELECT * FROM tblcustomer WHERE Token = ? AND Status ='Inactive' ");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $dbConn->prepare("UPDATE tblcustomer SET Status = 'Active', Token = NULL WHERE Username = ? ");
        $stmt->execute([$user['Username']]);
        $_SESSION['success'] = "Your email has been confirmed. You can now log in.";
        header("Location: login.php");
    } else {
        
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: login.php");
    }
} else {
    echo "No token provided.";
}
?>
