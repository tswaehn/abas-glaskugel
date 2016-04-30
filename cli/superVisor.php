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

  define("POLLING_TIME", 30); // [sec]
  
  date_default_timezone_set('Europe/Berlin');
  
  include( 'config.txt');
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

    $databaseIsUnlocked= getConfigDb( "dbLink" );
    if (!$databaseIsUnlocked){
      $text= '<span style="color:red;background-color:#DDDDDD;border:thin solid red;padding:10px;margin:10px;">database is blocked!</span>';
    } else {
      $text= '<span style="color:green;background-color:#DDDDDD;border:thin solid green;padding:10px;margin:10px;">everything is fine - database is unlocked</span>';
      $canExit= 1;
    }
    $text.= "\n";

    $emailText= "<h3>Glaskugel SuperVisor</h3>";
    $emailText.= "host ".$_SERVER["COMPUTERNAME"]." (".$_SERVER["HLS_IPADDR"].")<br>";
    
    $emailText.= date("r", $startTime). " +". ceil(($endTime-$startTime)/60)."min\n<p><p>";
    $emailText.= $text;

    echo $emailText;
    
    sendMail( "sven.ginka@gmail.com", "Glaskugel <> SuperVisor" , $emailText, array( LOG_FILE, ERROR_FILE, REPORT_FILE, DEBUG_FILE ) );
    
    if ($canExit){
      break;
    }
  }
  
  
  