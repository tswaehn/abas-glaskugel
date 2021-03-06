<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * this function is to check if the EDP sync process is ok 
 * it will send an email if someting went completely wrong
 * 
 */
  define("LOG_FILE", "./logging/standard.log");
  define("ERROR_FILE", "./logging/errors.log");
  define("REPORT_FILE", "./logging/report.log");
  define("DEBUG_FILE", "./logging/debug.log");


  date_default_timezone_set('Europe/Berlin');
  
  include( 'config.php');
  include( "emailSettings.php");
  
  include( 'dbConnection.php');
  include( 'smtpMail.php' );

  
  function lg($text){
    //echo $text;
  }
  function debug( $text ){
    //echo $text;
  }
  
  
  // script started already
  echo "superVisor script started\n";
  $startTime= time();
  $canExit= 0;
  
  for ($i=0;$i<10;$i++){
    // sleep for 30min
    echo "sleeping\n";
    time_sleep_until( time() + POLLING_TIME*60 );
    echo "waking up\n";
    // 
    $endTime= time();

    // now connect to db and check lock
    connectToDb();
	
    $text = '';
    $databaseIsUnlocked= getConfigDb( "dbLink" );
    if (!$databaseIsUnlocked){
      $text= '<span style="color:red;background-color:#DDDDDD;border:thin solid red;padding:10px;margin:10px;">database is unexpectedly still blocked!</span>';
    } else {
      //$text= '<span style="color:green;background-color:#DDDDDD;border:thin solid green;padding:10px;margin:10px;">everything is fine - database is unlocked</span>';
      $canExit= 1;
    }
    $text.= "\n";

    // do not send an email if everything is fine
    if ($canExit){
      break;
    }
    
    $emailText= "<h3>Glaskugel Sync Problem</h3>";
    $emailText.= "It looks like I need some help.\n";
    $emailText.= "host ".$_SERVER["COMPUTERNAME"]." (".BUILD_NR.")<br>";
    
    $emailText.= date("r", $startTime). " +". ceil(($endTime-$startTime)/60)."min\n<p><p>";
    $emailText.= $text;
    $emailText.= "<p><p>";
    $emailText.= "please see attached my logfiles or consider to restart syncing.\n";
    $emailText.= "contact r.zaspel@hseb-dresden.de to get the sync back online.\n\n";
    $emailText.= "Thank you\n-Glaskugel\n";

    echo $emailText;
    
    sendMail( $emailNotificationRecipients, "Glaskugel ".BUILD_NR." <> SuperVisor" , $emailText, array( LOG_FILE, ERROR_FILE, REPORT_FILE, DEBUG_FILE ) );
    
  }
  
  
  