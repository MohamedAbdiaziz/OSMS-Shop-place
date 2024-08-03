<?php
include_once('../db/session.php');

require_once("../db/DbConnect.php");

require '../vendor/autoload.php'; // Autoload PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    try {
        $db = new DbConnect();
        $dbConn = $db->connect();

        $sql = "SELECT * FROM tblcustomer WHERE Email = :email";
        $stmt = $dbConn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800;

            $sql = "INSERT INTO password_reset (email, token, expires) VALUES (:email, :token, :expires)";
            $stmt = $dbConn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires', $expires);
            $stmt->execute();

            $resetLink = "http://localhost:8082/osm/pages/resetpassword.php?token=" . $token;

            // Send the reset link via email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'onlineopticalshop9@gmail.com';           // SMTP username
                $mail->Password = 'tsty dmlh qtvu sdlf';              // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('no-reply@example.com', 'Optical Shop');
                $mail->addAddress($email);                            // Add a recipient

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "<h1>Username:".$customer['Username']." </h1>Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

                $mail->send();

                $_SESSION['success'] = "Password reset link has been sent to your email.";
                header("location: ../pages/forgetpassword.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                exit();
            }
        } else {
            $_SESSION['error'] = "No account found with that email address.";
             header("location: ../pages/forgetpassword.php");
                exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
         header("location: ../pages/forgetpassword.php");
                exit();
    }
}

?>