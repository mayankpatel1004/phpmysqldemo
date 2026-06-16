<?php
loadEnv(__DIR__ . '/.env');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/vendor/autoload.php';
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = base64_decode($_ENV['SMTPHOST']);
    $mail->SMTPAuth   = true;
    $mail->Username   = base64_decode($_ENV['SMTPMAIL']);
    $mail->Password   = base64_decode($_ENV['SMTPPASS']);
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = base64_decode($_ENV['SMTPPORT']);
    $mail->setFrom(base64_decode($_ENV['SMTPMAIL']), 'Test Mail');
    $mail->addAddress('mayank.patel104@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Hostinger SMTP Test';
    $mail->Body    = 'This is a test email using Hostinger SMTP (SSL 465)';
    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}


function loadEnv($file){
    if (!file_exists($file)) {
        die("❌ .env file not found: " . $file);
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}