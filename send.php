<?php
// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Enable detailed debug output
    $mail->SMTPDebug = 2;                     // 0 = off, 2 = detailed debug output
    $mail->Debugoutput = 'html';              // Debug output in HTML

    // SMTP server configurationC
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'prasaddarji60@gmail.com';        // ✅ Your Gmail address
    $mail->Password   = 'shkh ygmx rmxd vreh';           // ✅ Your Gmail App Password (not your Gmail login password)
    $mail->SMTPSecure = 'tls';                         // Or use 'ssl' with port 465
    $mail->Port       = 587;                           // TLS port: 587, SSL port: 465

    // Sender and recipient details
    $mail->setFrom('your_email@gmail.com', 'Your Name'); // Sender
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Receiver

    // Email content
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Test Dynamic Email';
    $mail->Body    = 'This is a <b>dynamic</b> email sent using PHPMailer in PHP!';
    $mail->AltBody = 'This is a plain-text version for non-HTML email clients.';

    // Send the email
    $mail->send();
    echo '✅ Email has been sent successfully!';
} catch (Exception $e) {
    echo "❌ Email could not be sent.<br>";
    echo "Mailer Error: {$mail->ErrorInfo}";
}
