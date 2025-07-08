<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
</head>
<body>
    <h2>Send Email</h2>
    <form action="send.php" method="POST">
        <label>Sender Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Receiver Email:</label><br>
        <input type="email" name="receiver_email" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="5" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
</body>
</html>
