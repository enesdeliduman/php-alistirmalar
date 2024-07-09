<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function send_email($to, $username, $subject, $message)
{
    $env = parse_ini_file('.env');
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username =  $env["EMAIL_USERNAME"];
        $mail->Password = $env["EMAIL_PASSWORD"];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('enesdeliduman@proton.me', 'Enes DELİDUMAN'); // Gönderen e-posta adresi ve adı
        $mail->addAddress($to, $username); // Alıcı e-posta adresi ve adı
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64'; // veya 'quoted-printable'

        $mail->send();
        echo 'E-posta başarıyla gönderildi!';
    } catch (Exception $e) {
        echo 'E-posta gönderilirken hata oluştu: ' . $mail->ErrorInfo;
    }
}
