<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './libs/phpmailer/src/Exception.php';
require './libs/phpmailer/src/PHPMailer.php';
require './libs/phpmailer/src/SMTP.php';

function send_email($recipientEmail, $mailInfo) {
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = MAILER_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAILER_USER_NAME;
    $mail->Password = MAILER_USER_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom(MAILER_FROM_EMAIL, MAILER_FROM_NAME);
    $mail->addAddress($recipientEmail);

    $mail->isHTML(true);
    $mail->Subject = $mailInfo['subject'];
    $mail->Body = $mailInfo['html'];

    $mail->send();

    return true;
  } catch (Exception $e) {
    echo 'Ошибка при отправке письма: ' . $e->getMessage();
    return false;
  }
}

