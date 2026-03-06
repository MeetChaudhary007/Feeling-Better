<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailr/vendor/autoload.php';
function sendotp($email,$otp)
{ 
try
{
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = 'smtp.gmail.com';  // server
$mail->Username = 'feelingbetter247@gmail.com';   //email id 
$mail->Password = 'myzbuettdnzlwvwi';   //16 character app password
$mail->Port = 465;                    //SMTP port
$mail->SMTPSecure = "ssl";

//sender information
$mail->setFrom('feelingbetter247@gmail.com', 'Team Feeling Better');

//receiver address and name
$mail->addAddress($email);

$mail->isHTML(true);

$mail->Subject = "Reset Password OTP";
$mail->Body    = "From Team Feeling Better, Your OTP for Password Reset is $otp";

    $mail->send();
    $en_email = base64_encode($email);
    $en_otp = base64_encode($otp);
    header("Location:reset-code.php?email=$en_email&otp=$en_otp");
}
catch (Exception $e) {
    echo $e;
    echo $mail->ErrorInfo;
}
  
$mail->smtpClose();
}