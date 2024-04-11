<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign variables and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                              // Send using SMTP
        $mail->Host       = 'your_smtp_server';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
        $mail->Username   = 'your_username';                           // SMTP username
        $mail->Password   = 'your_password';                           // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              // Enable implicit TLS encryption
        $mail->Port       = 465;                                      // TCP port to connect to; use 587 if using TLS

        // Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('to@example.com', 'Joe User');              // Add a recipient

        // Content
        $mail->isHTML(true);                                          // Set email format to HTML
        $mail->Subject = 'New contact form submission';
        $mail->Body    = "You have received a new message from $name ($email): <br>" . nl2br($message);
        $mail->AltBody = "You have received a new message from $name ($email): \n" . $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }




    // If you're saving to a database, use prepared statements to prevent SQL injection
    // Example using PDO:
    /*
    $pdo = new PDO('mysql:host=your_host;dbname=your_db', 'username', 'password');
    $query = 'INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)';
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);
    */

    // If you're sending an email, sanitize and validate email fields thoroughly
    // and consider using a library like PHPMailer for better security and reliability
}
?>
