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
    // sleep for 5min
    echo "sleeping\n";
    time_sleep_until( time() + 5*60 );
    echo "waking up\n";
    // 
    $endTime= time();

    // now connect to db and check lock
    connectToDb();

    $databaseIsUnlocked= getConfigDb( "dbLink" );
    if (!$databaseIsUnlocked){
      $text= '<span style="color:red;background-color:#DDDDDD;border:thin solid red;padding:10px;">database is blocked!</span>';
    } else {
      $text= '<span style="color:green;background-color:#DDDDDD;border:thin solid green;padding:10px;">everything is fine - database is unlocked</span>';
      $canExit= 1;
    }
    $text.= "\n";
        
    $emailText= date("r", $startTime). " +". ceil(($endTime-$startTime)/60)."min\n";
    $emailText.= $text;

    echo $emailText;
    
    sendMail( "sven.ginka@gmail.com", "Glaskugel <> SuperVisor" , $emailText );
    
    if ($canExit){
      break;
    }
  }
  
  
  