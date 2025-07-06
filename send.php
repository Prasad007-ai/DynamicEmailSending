<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);

try {

    $mail->SMTPDebug = 2;                     
    $mail->Debugoutput = 'html';             


    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'prasaddarji60@gmail.com';       
    $mail->Password   = 'shkh ygmx rmxd vreh';          
    $mail->SMTPSecure = 'tls';                        
    $mail->Port       = 587;                           


    $mail->setFrom('your_email@gmail.com', 'Your Name'); 
    $mail->addAddress('recipient@example.com', 'Recipient Name'); 


    $mail->isHTML(true);                                
    $mail->Subject = 'Test Dynamic Email';
    $mail->Body    = 'This is a <b>dynamic</b> email sent using PHPMailer in PHP!';
    $mail->AltBody = 'This is a plain-text version for non-HTML email clients.';


    $mail->send();
    echo '✅ Email has been sent successfully!';
} catch (Exception $e) {
    echo "❌ Email could not be sent.<br>";
    echo "Mailer Error: {$mail->ErrorInfo}";
}
