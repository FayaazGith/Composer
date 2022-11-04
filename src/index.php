<?php

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Monolog\Level;
use Monolog\logger;
use Monolog\Handler\StreamHandler;

$mail = new PHPMailer(true);

include 'private.php';

try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $username;
    $mail->Password   = $password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom($username, 'Klantenservice');
    $mail->addAddress($_POST['email'], $_POST['name']);
    $mail->addCC($username);
    $mail->isHTML(true);
    $mail->Subject = 'Uw klacht is in behandeling';
    $mail->Body    = $_POST['message'];
    $mail->send();
    echo 'Message has been sent';

    $logger = new Logger('info');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/info.log'));
    $logger->info('user data:', ['name' => $_POST['name'], 'emailaddress' => $_POST['email'], 'description' => $_POST['message']]);

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }