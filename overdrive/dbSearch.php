<?php
  

  
  
  function indexedSearch( $search ){
      $result = searchInTableX('Teil:Artikel', $search );
      echo '</pre>';    
      
      $count=$result->rowCount();
      
      echo "found ".$count." results";
      foreach ($result as $item){
      
	echo '<div id="item" style="border: thin solid blue; padding:10px; margin:10px">';
	  //echo shortArticle( $item );
	  echo $item["score"]. " ".$item["nummer"]." ".$item["such"]." ".$item["sucherw"];

	echo '</div>';
      }  
  }

  
  // search for first word in query exact match
  function articleSearchExact( $query ){
    
    echo "exact search ".$query."<br>";
    $split = preg_split( "/ /", $query, -1, PREG_SPLIT_NO_EMPTY );
    
    $what=array();
    foreach ($split as $item){
      if (strlen($item) > 0){
	$what[] = $item;
      }
    }

    $count = count($what);
    
    if ($count == 0){
      return null;
    }

    
    // option 1:
    // SELECT * FROM 
    //		(SELECT * FROM `dict` WHERE ( str LIKE '%kabel%' ) GROUP BY nummer) AS d0 
    //		INNER JOIN 
    //		(SELECT * FROM `dict` WHERE ( str LIKE '%w%' ) GROUP BY nummer ) as d1 
    //		ON (d0.nummer=d1.nummer) 
    //
    

    // begin sql
    $sql = "SELECT d0.* FROM ";
    
    
    // add each select line
    for ($i=0;$i< $count ;$i++){
      if ($i==0){
	$match="str ='".$what[$i]."'";
      } else {
	$match="str LIKE '%".$what[$i]."%'";
      }
      $select = "(SELECT * FROM ".q(DB_DICT)." WHERE ( ".$match." ) GROUP BY nummer) AS d".$i." ";
      
      if ($i > 0){
	$sql .= "INNER JOIN ";
      }
      
      $sql .= $select;
      
      if ($i > 0){
	$sql .= "ON (d0.nummer=d".$i.".nummer) ";
      }
      
    }
    
    // finalize sql query
    // $sql .= ";" ;
    // colon will be added in execute
  
    
    return $sql;      
  
  }
  
  function articleSearchLike( $query ){
    
    $split = preg_split( "/ /", $query, -1, PREG_SPLIT_NO_EMPTY );
    
    $what=array();
    foreach ($split as $item){
      if (strlen($item) >= 2){
	$what[] = $item;
      }
    }

    $count = count($what);
    
    if ($count == 0){
      return null;
    }

    
    // option 1:
    // SELECT * FROM 
    //		(SELECT * FROM `dict` WHERE ( str LIKE '%kabel%' ) GROUP BY nummer) AS d0 
    //		INNER JOIN 
    //		(SELECT * FROM `dict` WHERE ( str LIKE '%w%' ) GROUP BY nummer ) as d1 
    //		ON (d0.nummer=d1.nummer) 
    //
    

    // begin sql
    $sql = "SELECT d0.* FROM ";
    
    
    // add each select line
    for ($i=0;$i< $count ;$i++){
      $select = "(SELECT * FROM ".q(DB_DICT)." WHERE ( str LIKE '%".$what[$i]."%' ) GROUP BY article_id) AS d".$i." ";
      
      if ($i > 0){
	$sql .= "INNER JOIN ";
      }
      
      $sql .= $select;
      
      if ($i > 0){
	$sql .= "ON (d0.article_id=d".$i.".article_id) ";
      }
      
    }
    
    // finalize sql query
    // $sql .= ";" ;
    // colon will be added in execute
  
    
    return $sql;      
  
  }

  
  function filteredKeySearch( $query ){
    global $pdo;
    
    
    if (!preg_match("/ /", $query)){
      return null;
    }

    $split = preg_split( "/ /", $query, -1, PREG_SPLIT_NO_EMPTY );
    
    if (preg_match("/ $/", $query)){
      $restrictTo = "";    
    } else {
      $currentEdit = end($split);
      $restrictTo = " AND prop.str LIKE '%".$currentEdit."%' ";
    }
    
    
    
    // get SELECT statement for "search"
    $result = dbGetFromTable(DB_DICT, array("str"), "str='".$split[0]."'" );
    if ($result->rowCount() >= 0){ // mod
        $search = articleSearchLike( $query );
    } else {
	echo "<br> exact search ";
	$search = articleSearchExact( $query );
    }
    
    // search keywords of given set of articles
    // SELECT prop.str,count(*) as cnt FROM 
    //		(SELECT * FROM `dict` WHERE (...) ) AS search
    //		INNER JOIN 
    //		`dict` AS prop
    //		ON ( search.nummer=prop.nummer AND search.str != prop.str) 
    //		GROUP BY prop.str ORDER BY cnt DESC';

    $sql = "SELECT prop.str,count(*) as cnt FROM ";
    
    $sql .= "(".$search.") AS search ";
    $sql .= "INNER JOIN ";
    $sql .= q(DB_DICT)." AS prop ";
    $sql .= "ON ( search.article_id=prop.article_id AND search.str != prop.str ".$restrictTo.") ";
    $sql .= "GROUP BY prop.str HAVING cnt > 1 ORDER BY prop.str ASC";
    
    return $sql;
  }
  
  
  /*
    searches for items similar to $what and also returns their occurence count
  */
  function globalKeySearch( $query ){
    
    $split = preg_split( "/ /", $query, -1, PREG_SPLIT_NO_EMPTY );
    
    $what=array();
    foreach ($split as $item){
      if (strlen($item) >= 2){
	$what[] = $item;
      }
    }

    $count = count($what);
    
    if ($count == 0){
      return null;
    }
    
    $sql = "SELECT str,count(*) as cnt FROM ".q(DB_DICT)." WHERE str LIKE '%".$what[0]."%' GROUP BY str HAVING cnt > 1 ORDER BY str ASC";
  
    return $sql;  
    
  }
     
  

?>
