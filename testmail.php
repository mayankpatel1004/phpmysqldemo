<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'notifications@cloudswiftsolutions.com';
    $mail->Password   = 'demo@yopm';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('notifications@cloudswiftsolutions.com', 'Cloudswift Solutions');
    $mail->addAddress('mayank.patel104@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Hostinger SMTP Test';
    $mail->Body    = 'This is a test email using Hostinger SMTP (SSL 465)';
    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}