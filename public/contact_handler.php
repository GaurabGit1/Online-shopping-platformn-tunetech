<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include Composer's autoloader

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?status=error");
        exit();
    }

    // Sanitize user inputs to prevent XSS attacks
    $name = htmlspecialchars($name);
    $subject = htmlspecialchars($subject);
    $message = htmlspecialchars($message);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // SMTP server to send through (for Gmail)
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'gaurabbajgain84@gmail.com'; // SMTP username (your Gmail address)
        $mail->Password = 'mbwl ejuv vkqw opkv'; // SMTP password (your Gmail password or app-specific password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom($email, $name); // Sender's email and name
        $mail->addAddress('gaurabajgain1@gmail.com', 'Gaurab'); // Add your email here

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = "Contact Form: $subject"; // Set email subject
        $mail->Body    = "You have received a new message from the contact form on your website.\n\n";
        $mail->Body   .= "Name: $name\n";
        $mail->Body   .= "Email: $email\n";
        $mail->Body   .= "Subject: $subject\n";
        $mail->Body   .= "Message:\n$message\n";

        // Send email
        if ($mail->send()) {
            header("Location: contact.php?status=success");
        } else {
            header("Location: contact.php?status=error");
        }
    } catch (Exception $e) {
        // If an error occurs
        header("Location: contact.php?status=error");
    }
    exit();
} else {
    header("Location: contact.php");
    exit();
}
?>
