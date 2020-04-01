<?php
/**
 * This example shows making an SMTP connection with authentication.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Ho_Chi_Minh');

require '../vendor/autoload.php';
require '../function/function.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
configurePHPMailer($mail, 'Document Approval');
//Set who the message is to be sent to
$mail->addAddress('caotoanbk@gmail.com', 'John Doe');
//Set the subject line
$mail->Subject = 'PHPMailer SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
$mail->Body = "
<head>
<style>
table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}
</style>
</head>
<p>Dear </p>
<p>Request for Payment Plan</p>
<p><a href='http://117.4.94.32:88/approval/paymentview.php?payid=333'>Please follow this link and approval this request</a></p>
";
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('email_template.html'), __DIR__);
//Replace the plain text body with one created manually
$mail->IsHTML(true);
$mail->AltBody = '';
//Attach an image file
$mail->addAttachment('');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}