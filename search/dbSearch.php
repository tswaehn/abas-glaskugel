<?php

  function createFullTextIndex(){
    global $pdo;
    echo "<pre>";
  
    $table = 'search';
    
    $sql ="DROP TABLE IF EXISTS ".q($table).";";
    
    try {
	
	lg( $sql) ;
	
	$pdo->exec($sql);
	
	lg("removed $table Table.");

    } catch(PDOException $e) {
	lg( $e->getMessage() );//Remove in production code
    }  
      
    $sql = 'CREATE TABLE '.q($table).' ( ';
    $sql .= ' `id` INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,';
    $sql .= ' `nummer` VARCHAR(15),';
    $sql .= ' `such` VARCHAR(30),';
    $sql .= ' `sucherw` VARCHAR(255), ';
    
    $sql .= ' FULLTEXT ( `nummer`, `such`, `sucherw` )';
    $sql .= ' ) ENGINE=MYISAM DEFAULT CHARSET=utf8;';
    
    //$sql = 'create table '.q($table).' ( `a` INT, `b` VARCHAR(10) );';
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("query failed");
	return;
    } 
    print_r($result);
    lg('exec time is '.($timediff) );
  
 // ------------------  
    $sql = 'INSERT INTO '.q($table).' ( `nummer`, `such`, `sucherw`) SELECT `nummer`, `such`, `sucherw` FROM `Teil:Artikel` WHERE 1 ;';
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("query failed");
	return;
    } 
    print_r($result);
    lg('exec time is '.($timediff) );
    

    
    
    
  // ------------------  
    $sql = 'SLECT * FROM '.q($table).' WHERE MATCH (`nummer`, `such`, `sucherw`) AGAINST ( `bnc`);';
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("query failed");
	return;
    } 
    print_r($result);
    lg('exec time is '.($timediff) );
    
    echo "</pre>";
  }

  function searchInTableX( $table, $search ){
    global $pdo;
      
      
   //$match = "MATCH (`nummer`, `such`, `sucherw`) AGAINST ( '".$search."' IN BOOLEAN MODE )";
   $match1 = "MATCH (`nummer`, `such`, `sucherw`) AGAINST ( '".$search."' )";
   $match2 = "MATCH (`nummer`, `such`, `sucherw`) AGAINST ( '".$search."' IN BOOLEAN MODE  )";
   
   // ------------------  
    $sql = "SELECT *,".$match1." AS score FROM `search` WHERE ".$match2." ;";
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("query failed");
	return;
    } 
    
    print_r( $pdo->errorCode() );
    print_r( $pdo->errorInfo() );
    
    print_r($result);
    lg('exec time is '.($timediff) );
        
    lg('exec time is '.($timediff) );

    lg('found '.$result->rowCount().' items' );
    
    return $result;
  
  }


 function searchInTableY( $table, $search ){
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
    $sql .= ') GROUP BY ( `nummer` );';
    
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





?>
