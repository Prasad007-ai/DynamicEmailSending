<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    // Connect to DB
    $con = new mysqli("localhost", "root", "", "emailapp");
    if ($con->connect_error) {
        die("❌ DB connection failed: " . $con->connect_error);
    }

    // 1. Get sender credentials
    $stmt = $con->prepare("SELECT email, password FROM users WHERE LOWER(name) = LOWER(?) AND status = 'active'");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        echo "❌ No active sender found for name: $name";
        exit;
    }

    $row         = $result->fetch_assoc();
    $senderEmail = $row['email'];
    $senderPass  = $row['password'];
    $stmt->close();

    // 2. Get active receiver
    $receiverQuery = "SELECT email, name FROM receivers WHERE status = 'active' LIMIT 1";
    $receiverResult = $con->query($receiverQuery);

    if (!$receiverResult || $receiverResult->num_rows === 0) {
        echo "❌ No active receiver found in database.";
        exit;
    }

    $receiver = $receiverResult->fetch_assoc();
    $receiverEmail = $receiver['email'];
    $receiverName  = $receiver['name'];

    // 3. Send email
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
        $mail->addAddress($receiverEmail, $receiverName);

        $mail->isHTML(true);
        $mail->Subject = 'New message from ' . $name;
        $mail->Body    = '<b>' . htmlspecialchars($message) . '</b>';
        $mail->AltBody = $message;

        $mail->send();
        echo "✅ Email sent from $senderEmail to $receiverEmail!";
    } catch (Exception $e) {
        echo "❌ Failed to send email. Error: {$mail->ErrorInfo}";
    }

    $con->close();
}
?>
