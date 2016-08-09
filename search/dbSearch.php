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


  function getAllTagIDs( $searchFilters ){
    
    if (!is_array($searchFilters)){
      return;
    }
    
    $searchArr= array();
    foreach ($searchFilters as $group_name=>$item){
      foreach ($item as $tag_name){
        $searchArr[]= "(`group_name`='".$group_name."' AND `tag_name`='".$tag_name."')";
      }
    }
    $search= implode(" OR ", $searchArr);
    $sql= "SELECT * FROM `gk_article_groups` WHERE ".$search.";";
    $result = dbExecute( $sql );
    
    $tagIDs= array();
    foreach( $result as $item ){
      $tagIDs[]= $item["tag"];
    }
    
    return $tagIDs;
  }
  
  function getAllTagFilteredArticles( $tagIDs ){
    
    if (!is_array($tagIDs)){
      return;
    }
    
    // get all articles that have at least one of the tags
    $search= implode(",", $tagIDs);
    //$sql= "SELECT * FROM `gk_article_tags` WHERE `tag` IN (".$search."); ";
    //$sql= "SELECT *,count(*) as CNT FROM `gk_article_tags` WHERE `tag` IN (".$search.") GROUP BY `article_id` ORDER BY `CNT` DESC"; 
    $sql= "SELECT * FROM (SELECT *,count(*) as CNT FROM `gk_article_tags` WHERE `tag` IN (".$search.") GROUP BY `article_id` ORDER BY `CNT` DESC) AS T1 WHERE T1.CNT=".count($tagIDs);
    $result = dbExecute( $sql );
    
    // filter these articles that have ALL tags at the same time applied
    
    $validArticleIds= array();
    foreach( $result as $item ){
      $validArticleIds[]= $item["article_id"];
    }
    
    return $validArticleIds;
    
  }

?>
