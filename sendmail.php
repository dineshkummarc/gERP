<?php

require("PHPMailer/class.phpmailer.php");

$mail = new PHPMailer();
$mail->SetLanguage("en", "language");


$mail->IsSMTP();                                      // set mailer to use SMTP


$mail->SMTPAuth = true;     // turn on SMTP authentication




$mail->From = "gndec.sms.service@gmail.com";

$mail->FromName = "Mailer";
for($i=0;$i<=10;$i++){
$mail->AddAddress("harbhag.sohal@gmail.com");




$mail->WordWrap = 50;                              

//$mail->AddAttachment("test.php"); 



$mail->IsHTML(true);                                



$mail->Subject = "Here is the subject";

$mail->Body    = "Hello There ?";

$mail->AltBody = "This is the body in plain text for non-HTML mail clients";




if(!$mail->Send())

{

   echo "Message could not be sent. <p>";

   echo "Mailer Error: " . $mail->ErrorInfo;

   exit;

}
}



echo "Message has been sent";

?>
