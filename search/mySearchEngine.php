<?php
  
  function createLike( $cols, $value ){
    
    $likeStatement='';

    $likeStatement .='(';
    $first = $cols[0];
    foreach ($cols as $col){
      if ($col == $first){
	$likeStatement .= $col. " LIKE '%".$value."%'";
      } else {
	$likeStatement .= ' OR '.q($col). " LIKE '%".$value."%'";
      }
    } 
    
    $likeStatement .=')';
    
    return $likeStatement;
  }
  
  function showResultCount( $table, $searches, $cols ){
    global $pdo;
    foreach ($searches as $search){
      $sql = 'SELECT * FROM '.q($table).' WHERE (';
      
      $sql .= createLike($cols, $search);
      
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
      $count = $result->rowCount();
      
      print_r($count);
    }
  }
      
  function mySearchInTable( $table, $search, $options ){
    global $pdo;
    
    $searches = preg_split( "/( )/", $search, -1, PREG_SPLIT_NO_EMPTY );
    //print_r($searches);
    
    $columns = getColumns( $table );
    
    $sql = 'SELECT * FROM '.q($table).' WHERE (';
    
    $last=end($searches);
    foreach ($searches as $search ){
      $sql .= createLike( $columns, $search);
      if ($search!=$last){
	$sql .= ' AND ';
      }
    }
    
    // options
    if (isset($options["searchSparePart"]) && $options["searchSparePart"]==1){
      $sql.= ' AND '.q("ersatzt"). "='ja' ";
    }
    if (isset($options["searchSalesPart"]) && $options["searchSalesPart"]==1){
      $sql.= ' AND '.q("ycatsale"). "='ja' ";
    }
    
    
    //showResultCount($table, $searches, $columns );
    
    //$sql .= ' );';
    $sql .= ') GROUP BY ( `nummer` ) ORDER BY rank ASC;';
    
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

    if (!empty($result)){
      lg('found '.$result->rowCount().' items' );
    }
    
    return $result;

  }


  function mySearch( $search, $options ){
      
      $count = 0;
      
      $start=microtime(true);
      $result = mySearchInTable(DB_ARTICLE, $search, $options );
      $end=microtime(true);
      
      $diff = number_format( $end-$start, 3) ;      
      
      
      if (!empty($result)){
	$count=$result->rowCount();

	echo "found ".$count." results in ".$diff."secs";
	foreach ($result as $item){
	
	  echo '<div id="search_item">';
	  
		echo '<div style="height:60px; width:90px; float:left; margin-right:10px; text-align:center; ">';
            echo showThumbnail( $item );
		echo '</div>';
		
	    echo shortArticle( $item );
	  echo '</div>';
	}
      } else {
	
	//echo "empty result";
      }

      addClientInfo( $search );
      addClientInfo("res ".$count." ".$diff );
      
  }
  
  
  // ---
  
  $search = getUrlParam('search');
  if ($search ==''){
    $search ='';
  }
  
  $sparePart= getUrlParam('searchSparePart');
  if ($sparePart==''){
    $sparePart= 0;
  }
  $salesPart= getUrlParam('searchSalesPart');
  if ($salesPart==''){
    $salesPart= 0;
  }
  
  
  $search = preg_replace( ALLOWED_ASCII, " ", $search );
  $search = trim( $search );
  
  
  $options= array( "searchSparePart"=>$sparePart, "searchSalesPart"=>$salesPart );
  
  echo '<div id="searchresult">';
  mySearch($search, $options);
  echo '</div>';
  


?>
