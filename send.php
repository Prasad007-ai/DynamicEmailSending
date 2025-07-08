<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = $_POST['name'];
    $receiverEmail  = $_POST['receiver_email'];
    $message        = $_POST['message'];

    // DB connection
    $con = new mysqli("localhost", "root", "", "emailapp");
    if ($con->connect_error) {
        die("❌ DB connection failed: " . $con->connect_error);
    }

    // Get sender from DB
    $stmt = $con->prepare("SELECT email, password FROM users WHERE LOWER(name) = LOWER(?) AND status = 'active'");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row         = $result->fetch_assoc();
        $senderEmail = $row['email'];
        $senderPass  = $row['password'];

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $senderEmail;
            $mail->Password   = $senderPass;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom($senderEmail, $name);
            $mail->addAddress($receiverEmail); 
            $mail->isHTML(true);
            $mail->Subject = 'New message from ' . $name;
            $mail->Body    = '<b>' . htmlspecialchars($message) . '</b>';
            $mail->AltBody = $message;

            $mail->send();
            echo "✅ Email sent from $senderEmail to $receiverEmail!";
        } catch (Exception $e) {
            echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "❌ No active sender found in the database for name: $name";
    }

    $stmt->close();
    $con->close();
}
?>
