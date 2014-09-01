<?php

  $sender = "sven@copiino.cc";
  $empfaenger = "sven@copiino.cc";
  $betreff = "Hier kommt eine eMail von $sender";
  $mailtext = "Moin Heinz!\nIch hoffe Deine eMailAdresse $empfaenger existiert noch.";
  $res=mail($empfaenger, $betreff, $mailtext, "From: $sender "); 
  
  if ($res){
    echo "mail sent";
  } else {
    echo "failed to send mail";
  }

?>
