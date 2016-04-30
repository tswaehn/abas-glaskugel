<?php
  
  date_default_timezone_set('Europe/Berlin');
  
  include( 'config.txt');
  include( "emailSettings.php");

  echo "<pre>";
  $starttime = microtime(true);
  
  include( 'logging.php');
  include( '../lib/lib.php');
  include( 'dbConnection.php');
  include( './EDP/EDPDefinition.php');
  include( './EDP/EDPConsole.php');
  include( 'prepareDatabase.php');
  include( 'smtpMail.php' );
  
  initLogging();
  
  lg( "---" );
  lg( "CLI EDP importer ".BUILD_NR );
  
  lg( date("r") );
  lg( "start" );
  
  connectToDb();
  
  setupConfigDb();
  
  // 
  lockDb();

  if (defined("DO_IMPORT_FROM_EDP")){
    // 
    backTrace("EDP import");
    lg("EPD import started");
    
    createEDPini();

    // load complete table info from EDP
    getEDPTables();

    $tables = getEDPDefinition();

    foreach ( $tables as $table ){

      // get specific table info
      $tablename = $table->tablename;
      $searches= $table->searches;

      // load and store field definitions from EDP
      $fieldinfo = getEDPFieldNames( $tablename );

      // execute the search and insert into db
      $first=1;
      foreach ($searches as $search){
        if ($first){
          $first=0;
          $mode=_CLEAN_;
        } else {
          $mode=_UPDATE_;
        }
        importTable( $tablename, $fieldinfo, $search, $mode );
      }

    }

    lg( "EDP import done" );
  }
  
  lg("preare database started");
  prepareDatabase();
  lg("prepare databease done");

  //
  unlockDb();
  
  setConfigDb( "lastSync", date("r") );
  
  // done
  $endtime = microtime(true); 
  $timediff = $endtime-$starttime;
  report( 'total exec time is '.($timediff).'sec ('.$timediff/60 .' min)' );   
    
  lg( date("r") );
  lg( "done" );
  
  lg( "bye" );
    
  echo "</pre>";  

  $emailText= "<h3>Glaskugel Sync Report</h3>";
  $emailText.= "host ".$_SERVER["COMPUTERNAME"]." (".$_SERVER["HLS_IPADDR"].")<br>";
  $emailText.= '<pre style="border:thin solid gray; background-color:#DDDDDD;margin:10px;padding:10px;font-size:15px">';
  $emailText.= file_get_contents( REPORT_FILE );
  $emailText.= '</pre>';
  $emailText.= "<br>thats all for now. see you tomorrow.<br>buy<br>";
  sendMail( "sven.ginka@gmail.com", "Glaskugel Sync Report" , $emailText );

?>

