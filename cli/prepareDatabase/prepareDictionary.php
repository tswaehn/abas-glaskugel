<?php

  function dbCreateTableDict(){
    
    $table = DB_DICT;
  
    $fields = array( "id", "str", "article_id");
    $fieldinfo["id"]["type"]=INDEX;
    $fieldinfo["id"]["size"]=0;
    $fieldinfo["str"]["type"]=ASCII;
    $fieldinfo["str"]["size"]=30;
    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;
    

    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    createTable( $table, $fields, $fieldinfo );
    
  }

  function cleanupString( $string ){
    // search all in lowercase only
    $string = mb_strtolower($string,'UTF-8'); 

        
    // replace [-10-] "asfasfas[-10-]klajflaksjdfl"
    $string = preg_replace( "/\[\-10\-\]/", " ", $string );

    // remove zeichn "004.00-1600.026F-03.01"
    $string = preg_replace( "/\d{3}\.\d{2}.*\d{2}\.\d{2}/", "", $string );

    // remove zeiss "475820-0115-000/01" or "475610-0436-000"
    $string = preg_replace( "/\d{6}\-\d{4}\-\d{3}|\/\d{2}/", "", $string );
    
    // replace special chars/unwanted chars by separator
    $string = preg_replace( "/[^A-Za-z0-9\ö\ä\ü\Ö\Ä\Ü\ß\.]/", " ", $string );
    
    if (is_numeric($string)){
      $string = "";
    }
    
    return $string;
  }
  
  function dictSplit( $item ){

    //print_r( $item );
    
    $dict_str = "";
    
    foreach ($item as $key=>$str_val){
      if (is_numeric($key)){
	continue;
      }

      /*
      \todo
      leere felder entfernen
      */
      switch ($key){
	case "article_id":
	case "erfass":	
	case "stand":
	    $str = "";
	    break;
	
	case "nummer":
	    $str = $item["nummer"];
	    break;

	case "zeichn":
	    $str = $item["zeichn"];
	    break;
	case "yzeissnr":
	    $str = $item["yzeissnr"];
	    break;
	    
	case "name":
	    // name
	    $tmp = $item["name"];

	    // replace "@@ ..."
	    $tmp = preg_replace( "/\@\@.*/", "", $tmp );
	    
	    $str = cleanupString( $tmp );
	    break;
	    
	default:
	  $str = cleanupString( $str_val );
      }
      
      $dict_str = $dict_str." ".$str;
    
    }

    //print_r( $dict_str );
    
    // split by separator
    $dict=preg_split( "/ /", $dict_str, -1, PREG_SPLIT_NO_EMPTY );
    
    // remove double entries
    $dict = array_unique( $dict );
    
    // todo:
    // - remove single chars
    // - replace sub-double entries ex: remov "cable" where specific "cameracable" exists
    // - implement blacklist
    // - remove "@@ ... " but !!7900-00001 !!
    // - add more fields to index
    
    
    return $dict;
  }

  function dbAddToDict( $article_id, $values ){
    global $pdo;

    $fields = array( "str", "article_id" );
    foreach ($values as $value){
	insertIntoTable( DB_DICT, $fields, array( array( $value, $article_id )) );
    }
  }
  
  function dbCreateDict(){
    echo 'creating dict ';
    $starttime = microtime(true); 
    
    dbCreateTableDict();
    
    //$fields = array( "article_id", "nummer", "such", "name", "ebez", "bsart", "ynlief","zeichn","lief","lief2","yersteller","yzeissnr"  );
    $fields = array( "article_id", "nummer", "such", "name", "ebez", "bsart", "ynlief","zeichn","yersteller","yzeissnr"  );    
    
    $result = dbGetFromTable( DB_ARTICLE, $fields, "", 100000 );

    $count = $result->rowCount();
    
    $i=0;
    $k=0;
    
    $outputCount=10;
    
    $dataSet = array();
    
    foreach ($result as $item ){
    
      $i++;
      $k++;
      if ($i>$outputCount){
	$i=0;
	echo "\n";
	$percent= ($k / $count) * 100;
	
	$elapsed_time = microtime(true)-$starttime; 
	
	$remain_time = ($elapsed_time/$percent)*100 - $elapsed_time;
	
	echo number_format($percent, 2, '.', '')."% ".number_format($elapsed_time, 1, '.', '')."secs remain: ".number_format($remain_time, 1, '.', '')."secs >";
	
	echo $k." of ".$count;
      }

      //dbAddToDict( $item["article_id"], $dict );
      
      $dict = dictSplit( $item );
      $article_id = $item["article_id"];
      
      foreach ($dict as $value){
	$dataSet[] = array( $value, $article_id);      
      }
      
      
    
    
    }
    
    $fields = array( "str", "article_id" );
    insertIntoTable( DB_DICT, $fields, $dataSet );
    
    
    $endtime = microtime(true); 
    $timediff = $endtime-$starttime;
    echo '\n exec time is '.($timediff);    
    
  }
  


  function dbCreateRank(){
    
    // for faster access: calculate data once and store into temp table
    $table = DB_DICT_RANK;
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    
    $sql = "CREATE TABLE ".q($table)." AS SELECT id,str,count(*) AS cnt FROM ".q(DB_DICT)." GROUP BY `str` ";
    $rankRes = dbExecute( $sql );
    

    // --- 
    $table = DB_ARTICLE;
    
    // get all entries by id
    $result = dbGetFromTable( $table, array("article_id"), "rank IS NULL", 100000 );
    
    
    //hier zuvor noch dict_rank aus "count" des dict erstellen !!
    
    foreach ($result as $item){
      
      $article_id = $item["article_id"];
      
      //$sql = "SELECT article_id,sum(cnt) AS rank FROM (SELECT * FROM `dict` WHERE (article_id=".$article_id.")) AS d0, dict_rank AS d1 WHERE d0.str=d1.str GROUP BY d0.article_id";
      $sql = "SELECT sum(cnt) AS rank FROM ".q(DB_DICT_RANK)." WHERE (str) IN (SELECT str FROM ".q(DB_DICT)." WHERE article_id=".$article_id.")";
      $rankRes = dbExecute( $sql );
      $res = $rankRes->fetch();

      $sql = "UPDATE ".q(DB_ARTICLE)." SET rank = ".$res["rank"]." WHERE article_id = ".$article_id;
      dbExecute( $sql );
            
      echo $article_id." ".$res["rank"];
    }
    
  }
  
  
 ?>
