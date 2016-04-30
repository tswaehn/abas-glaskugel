<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  
  include("./PHPMailer/PHPMailerAutoload.php");

function sendMail($email, $subject, $text){
  global $emailSettings;
  
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->CharSet = 'UTF-8';

  $mail->Host       = $emailSettings->host; // SMTP server example
  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->Port       = $emailSettings->port;                    // set the SMTP port for the GMAIL server
  $mail->Username   = $emailSettings->user; // SMTP account username example
  $mail->Password   = $emailSettings->pass;        // SMTP account password example
  $mail->setFrom( $emailSettings->from );
  
  $mail->isHTML(true); 

  $mail->addAddress( $email );
  
  
  $text= str_replace( "\n", "<br>", $text);
  $mail->Subject= $subject;
  $mail->Body= $text;
  $mail->AltBody= "HTML content needed";
  
  //send the message, check for errors
  if (!$mail->send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
      echo "Message sent!";
  }
  
}