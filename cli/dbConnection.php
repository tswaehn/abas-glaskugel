<?php
  //phpinfo();
  define('_UPDATE_', 'update' );
  define('_CLEAN_', 'clean' );
  define('_FIELD_PREFIX_', 'a_' );
  
  // table type defines
  define( "ASCII", 0 );
  define( "FLOAT", 1 );
  define( "TIMESTAMP", 2 );
  define( "INDEX", 3 );
  define( "INT", 4 );
  
  // table names
  define( "DB_CONFIG", "gk_config" );
  define( "DB_ARTICLE", "gk_article" );
  define( "DB_PRODUCTION_LIST", "gk_production_list" );
  define( "DB_DICT", "gk_dict");
  define( "DB_DICT_RANK", "gk_dict_rank" );  
  define( "DB_CLIENT_ACCESS", "gk_client_access" );
  define( "DB_ORDERS", "gk_orders" );
  define( "DB_STORAGE", "gk_storage_places" );
  
  
  // allowed characters for any ASCII text field formated as regex for replace
  define( "ALLOWED_ASCII", "/[^A-Za-z0-9.\-\ \@\ö\ä\ü\Ö\Ä\Ü\ß]/" );
  
  
  function q($text){
    return "`".$text."`";
  }

  function connectToDb(){
    global $dbname,$user,$pass,$pdo;
    
    $opt = array(
      // any occurring errors wil be thrown as PDOException
      //PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
      // an SQL command to execute when connecting
      //PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
    );
    
    $pdo = new PDO('mysql:host=localhost;dbname='.$dbname.';', $user, $pass, $opt);  
    // set connection to be able to communicate in UTF8
    $pdo->exec("set names utf8");
    
    lg("--- db connected to ".$dbname );
  }
  
  function tableExists( $table ){
    global $pdo;
    
    try {
	$sql = "SELECT 1 FROM ".q($table)." LIMIT 1;";
	lg($sql);
	$result = $pdo->query( $sql);
    } catch (Exception $e) {
	lg( $e->getMessage() );
	// We got an exception == table not found
	return FALSE;
    } 
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
  }
  
  function dbExecute( $sql ){
    global $pdo;
    
    if (empty($sql)){
      return null;
    }
    
    $sql .= ";";
    lg($sql);   
    
    try {

	$starttime = microtime(true); 
	
	$result = $pdo->query( $sql);
	
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
	
	lg('exec time is '.($timediff) );
	
    } catch (Exception $e) {
	lg("exec failed");
	return;
    } 
    
    if (!empty($result)){
      lg('found '.$result->rowCount().' rows' );
    } else {
      lg('result empty');
    }
    
    return $result;  
  }
  
  function setConfigDb( $key, $value ){
    
    $sql = "INSERT INTO ".q(DB_CONFIG)." (`key`,`value`) VALUES ('".$key."', '".$value."') ";
    $sql .= "ON DUPLICATE KEY UPDATE `key`='".$key."', `value`='".$value."'";
    dbExecute( $sql );
  }
  
  function getConfigDb( $key ){
    $value = "";
    
    $sql = "SELECT `value` FROM ".q(DB_CONFIG)." WHERE `key`='".$key."' LIMIT 1";
    $result = dbExecute( $sql );
    if ($result->rowCount()){
      $item = $result->fetch();
      $value = $item["value"];
    }
    
    return $value;
  }
  
  function createTable( $table, $fields, $fieldinfo ){
    global $pdo;
    
    $field_str="";
    $count=count($fields);
    for ($i=0;$i<$count;$i++){
      $field = $fields[$i];
      
      $type=$fieldinfo[$field]['type'];
      
      $type_str = "";
      switch ($type){
	case ASCII: $type_str = "VARCHAR(255)";break;
	case FLOAT: $type_str = "FLOAT";break;
	case TIMESTAMP: $type_str = "DATETIME";break;
	case INDEX: $type_str= "BIGINT(32) NOT NULL AUTO_INCREMENT, PRIMARY KEY ($field)";break;
	case INT: $type_str= "BIGINT(32)";break;
	
	default:
	  $type_str = "TEXT";
	  lg( "failed to set type ");
      }
      
      if (isset($fieldinfo[$field]['additional'])){
	$type_str .= " ".$fieldinfo[$field]['additional'];
      }
      
      $field_str .= " ".q($field)." ".$type_str;
      if ($i <($count-1)){
	$field_str.=",";
      }
    }

    try {
        $sql ="CREATE table ".q($table)." (".$field_str.") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_german2_ci;";
	
	lg( $sql) ;
	$q=$pdo->query($sql);
	

    } catch(PDOException $e) {
	lg("failed to create table ".$table);
	lg( $e->getMessage() );//Remove in production code
	return;
    }  
    
    lg("Created ".$table." Table.");
  
  }
  
  function removeTable( $table ){
    global $pdo;
    
    try {
	$sql ="DROP TABLE IF EXISTS ".q($table).";";
	
	lg( $sql) ;
	
	$pdo->exec($sql);
	
	lg("removed $table Table.");

    } catch(PDOException $e) {
	lg( $e->getMessage() );//Remove in production code
    }  
  
  }
  
  function insertIntoTable( $table, $fields, $lines ){
    global $pdo;
    
    
    $field_str ="";
    $last = end($fields);
    foreach ($fields as $field){
      $field_str .= "`".$field."`";
      if ($field!=$last){
	$field_str .=",";
      }
    }
    
    
    $placeholder="";
    $count=count($fields);
    for ($i=0;$i<$count;$i++){
      $placeholder .= "?";
      if ($i < ($count-1)){
	$placeholder .= ",";
      }
    }

    $total_count=count($lines);
    lg( "inserting ".$total_count." data sets");
    $step_size = $total_count / 100;
    
    $total=0;
    $k=0;
    // query
    try {
      $sql = "INSERT INTO ".q($table)." (".$field_str.") VALUES (".$placeholder.")";
      lg($sql);
      
      $q = $pdo->prepare($sql);
      
      foreach ($lines as $line){

	$data = array();
	for ($i=0;$i<$count;$i++){
	  //$data[] = preg_replace("/[^A-Za-z0-9\-\ ]/", "", $line[$i]);
	  $data[] = $line[$i];

	}
	
	$q->execute( $data );
    
	$total++;
	$k++;
	if ($k>=$step_size){
	  $k=0;
	  $percnt = $total/$total_count * 100;
	  lg( "insert ".$percnt."% (".$total."/".$total_count.")" );
	}
      }
    } catch(PDOException $e) {
      lg( "something went wrong while inserting data");
      return;
    }
    
    //lg( "insert into tables complete" );
  
  }
  
  function getLastInsertIndex(){
    global $pdo;

    $sql = "SELECT LAST_INSERT_ID() as ID;";
    //lg($sql);
    
    // query
    try {
      
      $q = $pdo->query($sql);
      
    } catch(PDOException $e) {
      lg( "something went wrong while requesting index");
      return;
    }
    
    $row=$q->fetch();
    
    if (isset($row["ID"])){
      $index = $row["ID"];
    } else {
      $index = -1;
    }
    //lg( "last index is ".$index );  
    
    return $index;
  }

  function getColumns( $table ){
    global $pdo;
    
    $sql = 'SHOW COLUMNS FROM '.q($table).';';

    try {
	lg($sql);
	$result = $pdo->query( $sql);
    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
    
    $columns = array();
    foreach( $result as $item ){
      $columns[] = $item['Field'];
    }
    
    return $columns;
  }

  function dbGetFromTable( $table, $fields="", $search="", $limit=5, $offset=0 ){
    global $pdo;
    
    if (is_array($fields)){
      $fields_str = implode( ",", $fields );
    } else {
      $fields_str = "*";
    }
    if (empty($search)){
      $search = "1";
    }
    
    $sql = 'SELECT '.$fields_str.' FROM '.q($table).' WHERE ('.$search.') LIMIT '.$limit.' OFFSET '.$offset;
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
    
    lg('exec time is '.($timediff) );
    
    lg('found '.$result->rowCount().' items' );
    
    return $result;  
  
  }  
  
  function searchInTable( $table, $search, $group="nummer" ){
    global $pdo;
    
    $columns = getColumns( $table );
    
    $sql = 'SELECT * FROM '.q($table).' WHERE (';
    
    $first = $columns[0];
    foreach ($columns as $item){
      if ($item == $first){
	$sql .= $item. " LIKE '%".$search."%'";
      } else {
	$sql .= ' OR '.q($item). " LIKE '%".$search."%'";
      }
    }

    //$sql .= ' );';
    $sql .= ') GROUP BY ( `'.$group.'` );';
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
    
    lg('exec time is '.($timediff) );

    lg('found '.$result->rowCount().' items' );
    
    return $result;
  
  }
  
  function prepareTable( $table, $fields, $fieldinfo, $mode ){
    global $pdo;
    
      
    if (tableExists( $table )){
      lg( "table ".$table." exists" );
      
      if ($mode == _CLEAN_){
	removeTable( $table );
	createTable( $table, $fields, $fieldinfo );
      }
      
    } else {
      lg( "table ".$table." does not exist" );
      createTable( $table, $fields, $fieldinfo );
    }
    
    if ($mode == _UPDATE_ ){
    
    
    }
  }
   
  
  function importTable( $table, $fieldinfo, $search, $mode ){
    
    // array with 
    $data = getEDPData( $table, $search );
    
    //renderData( $data );
    
    $fields=$data['fields'];
    $lines=$data['lines'];

    prepareTable( $table, $fields, $fieldinfo, $mode );
  
    insertIntoTable( $table, $fields, $lines );
    
  }

?> 
