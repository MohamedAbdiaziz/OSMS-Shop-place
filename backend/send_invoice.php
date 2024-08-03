<?php
require '../vendor/autoload.php'; // Autoload PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_invoice_email($to, $items) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'onlineopticalshop9@gmail.com';           // SMTP username
        $mail->Password = 'tsty dmlh qtvu sdlf';    
        $mail->Port = 587;

        $mail->setFrom('no-reply@example.com', 'Optical Shop');             
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Your Order Invoice';
        
        $bodyContent = '<h1>Thank you for your purchase!</h1>';
        $bodyContent .= '<p>Here are the details of your order:</p>';
        $bodyContent .= '<table border="1" cellspacing="0" cellpadding="5"><thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead><tbody>';
        
        foreach ($items as $item) {
            $bodyContent .= "<tr><td>{$item['ProductName']}</td><td>{$item['ProductPrice']}</td><td>{$item['Quantity']}</td></tr>";
        }
        
        $bodyContent .= '</tbody></table>';
        
        $mail->Body = $bodyContent;

        $mail->send();
        // echo 'Invoice email has been sent';
    } catch (Exception $e) {
        echo "Invoice email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
