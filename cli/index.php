<?php

  date_default_timezone_set('Europe/Berlin');
  
  include( 'config.txt');
  
  define( "CLI", true );

  echo "<pre>";
  $starttime = microtime(true);
  
  include( '../lib/lib.php');
  include( 'dbConnection.php');
  include( 'EDPDefinition.php');
  include( 'EDPConsole.php');
  include( 'prepareDatabase.php');

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

    lg( "import done" );
  }
   prepareDatabase();

  //
  unlockDb();
  
  setConfigDb( "lastSync", date("r") );
  
  // done
  $endtime = microtime(true); 
  $timediff = $endtime-$starttime;
  lg( '--- total exec time is '.($timediff).'sec ('.$timediff/60 .' min)' );   
    
  lg( date("r") );
  lg( "done" );
  
  lg( "bye" );
  
  file_put_contents("log.txt", $logging);
  
  echo "</pre>";  


?>

