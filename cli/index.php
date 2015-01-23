<?php

  date_default_timezone_set('Europe/Berlin');
  include( 'lib.php');
  include( 'dbConnection.php');
  include( 'EDPDefinition.php');
  include( 'EDPConsole.php');
  include( 'prepareDatabase.php');

  echo "<pre>";
  lg( "start" );
  
  
  connectToDb();
  
  setupConfigDb();
  
  // 
  lockDb();
 
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
 
   prepareDatabase();

  //
  unlockDb();
  
  setConfigDb( "lastSync", date("r") );
  
  // done
  lg( "bye" );
  echo "</pre>";  


?>

