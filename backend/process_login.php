<?php
include_once('../db/session.php');

require_once("../db/DbConnect.php");

$username = $_POST['username'];
$password = md5($_POST['password']);
// echo "Hello ".$username.$password;
// $_SESSION['error'] = "Invalid username or password.";
//         header("Location: ../pages/login.php");
// exit();

try {
    $db = new DbConnect();
    $dbConn = $db->connect();

    $sql = "SELECT * FROM tblcustomer WHERE Username = :username AND Status='Active'";
    $stmt = $dbConn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($password === $user['Password']) {
        $_SESSION['customer'] = $user['Username'];
        $_SESSION['customer_name'] = $user['Name'];
        header("Location: ../pages/account.php");
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../pages/login.php");
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: ../pages/login.php");
}
?>
