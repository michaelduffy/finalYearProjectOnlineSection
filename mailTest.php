<?php


/*$to = "mictriduf@hotmail.co.uk";
$subject = "message subject";
$message = "mail message";
mail ($to , $subject ,$message );*/

// The message
$message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Send
$mailCheck = mail('mictriduf@hotmail.co.uk', 'My Subject', $message);

print("mail Check = ".$mailCheck); 


/*require("PHPMailer/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();    // set mailer to use SMTP
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.hotmail.com";    // specify main and backup server
$mail->SMTPAuth = true;    // turn on SMTP authentication
$mail->Username = "mictriduf@hotmail.co.uk";    // SMTP username -- CHANGE --
$mail->Password = "mariatriathlon79";    // SMTP password -- CHANGE --
$mail->Port = "465";    // SMTP Port

$mail->From = "mictriduf@hotmail.co.uk";    //From Address -- CHANGE --
$mail->FromName = "GambleCraft";    //From Name -- CHANGE --
$mail->AddAddress("mictriduf@hotmail.co.uk", "Example");    //To Address -- CHANGE --
$mail->AddReplyTo("mictriduf@hotmail.co.uk", "Your Company Name"); //Reply-To Address -- CHANGE --

$mail->WordWrap = 50;    // set word wrap to 50 characters
$mail->IsHTML(false);    // set email format to HTML

$mail->Subject = "AuthSMTP Test";
$mail->Body    = "AuthSMTP Test Message!";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent"; */


?>