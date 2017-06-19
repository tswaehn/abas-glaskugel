<?php

  function getArticleByAbasNr( $abas_nr ){
    global $pdo;
    
    $table=DB_ARTICLE;
    
    $sql = "SELECT * FROM ".q($table)." WHERE ( nummer = :abas_nr ) LIMIT 1;";
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	
	$result = $pdo->prepare( $sql);
	$data = array( ":abas_nr" => $abas_nr );
	$result->execute( $data );
	
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;

    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
      
    lg('exec time is '.($timediff) );
    //lg('found '.$result->rowCount().' items' );
    
    return $result;
  
  }
  
  function getArticle( $articleId ){
    global $pdo;
    
    $table=DB_ARTICLE;
    
    $sql = "SELECT * FROM ".q($table)." WHERE ( article_id = :article_id ) LIMIT 1;";
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	
	$result = $pdo->prepare( $sql);
	$data = array( ":article_id" => $articleId );
	$result->execute( $data );
	
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;

    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
      
    lg('exec time is '.($timediff) );
    //lg('found '.$result->rowCount().' items' );
    
    return $result;
  
  }
  
  /**
   * return last 'human' editor
   * (not in botlist)
   * 
   * @param unknown $articleId
   * @return Array last editor and time
   */
  function getLastEditor($article) {
  	global $pdo;
  	
  	$sql = 'SELECT * FROM ' . DB_LASTEDIT . ' WHERE (article_id = :article_id) LIMIT 1';
  
  	$result = $pdo->prepare($sql);
  	$data = array(":article_id" => $article['article_id']);
  	$ret = $result->execute($data);
  	//if no result (no real editor known by now)
  	if (!$ret || $result->rowCount() == 0) {
  		//use original (bot) data
  		$result = array(
  					'article_id' => $article['article_id'],
  					'edittime' => $article['stand'],
  					'editor' => $article['zeichen'],
  				);
  	} else {
  		$result = $result->fetch();
  	}
  	
  	return $result;
  }

  
  function getSimilarItems( $article ){
    global $pdo;
    
    $s_abasNr = '%'. substr($article["nummer"],0,-1) .'%';
    //$s_such = '%'.preg_replace('/[^\da-z]/i', '%', $article["such"] ).'%';
    //$s_such = '%'. preg_replace("/[^A-Za-z\.\/]/", '%', $article["such"]) .'%';  
    $table=DB_ARTICLE;
    
    $fields = "such,ebez,nummer,kenn,rank,bsart,ynlief,zeichn,article_id,name,vkbez,vbezbspr,vkbez2,vbez2,ve,dbestand,lgdbestand,dbestand,zbestand,bestand,dbestand,lgbestand";
    $sql = "SELECT " . $fields. " FROM ".q($table)." WHERE ( nummer like :abasNr AND nummer <> :nummer ) GROUP BY (`nummer`);";
    
    try {
	lg($sql);
	$result = $pdo->prepare( $sql);
	//$data = array( ":nummer"=> substr($article["nummer"],0,-1), ":name" => $article["name"], ":such" => $article["such"] );
	//$data = array( ":nummer"=> '%'.substr($article["nummer"],0,-1).'%', ":abas_nr"=> 'x'.$article["nummer"] );
	$data = array( ":abasNr"=> $s_abasNr, ":nummer" => $article["nummer"] );

	
	$starttime = microtime(true);
	$result->execute( $data );
	$endtime = microtime(true);
	$timediff = $endtime-$starttime;
	
	debug('exec time is '.($timediff) );
	
    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
    
    //lg('found '.$result->rowCount().' items' );
    
    return $result; 
  
  
  }



?>

