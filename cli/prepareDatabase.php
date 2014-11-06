<?php
  
  function setupConfigDb(){
    
    if (!tableExists(DB_CONFIG) ){
      $fields = array( "key", "value" );

      $fieldinfo["key"]["type"]=ASCII;
      $fieldinfo["key"]["size"]=30;
      $fieldinfo["key"]["additional"]='UNIQUE';
      $fieldinfo["value"]["type"]=ASCII;
      $fieldinfo["value"]["size"]=200;
      
      createTable( DB_CONFIG, $fields, $fieldinfo );
    }
    
  }
  
  function lockDb(){
    setConfigDb("dbLink", 0);
  }
  
  function unlockDb(){
    setConfigDb("dbLink", 1);
  }
  
  
  function dbCreateTableArticle(){
  
    $table = DB_ARTICLE;
    
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $new_table_fields = array( "article_id", "rank", "nummer", "such", "name", "ebez", 
		     "bsart", "ynlief", "zuplatz", "abplatz",  
		     "bestand", "lgbestand", "zbestand", "dbestand", "lgdbestand", 
		     "ve", "fve",
		     "zeichn","lief","lief2","yersteller","yzeissnr",
		     
		     "ypdf1", "ydxf", "yxls", "ytpdf", "ytlink", "bild", "bbesch", "foto",
		     "fotoz", "catpics", "catpicsz", "catpiclz", "caturl",
		     
		     "erfass", "stand", "zeichen",
		     
		     "bem", "kenn", "bstnr", "vbezbspr", "vkbezbspr", "ftext"
		     );

    $fieldinfo["article_id"]["type"]=INDEX;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["rank"]["type"]=INT;
    $fieldinfo["rank"]["size"]=0;
    
    $fieldinfo["nummer"]["type"]=ASCII;
    $fieldinfo["nummer"]["size"]=15;

    $fieldinfo["such"]["type"]=ASCII;
    $fieldinfo["such"]["size"]=30;

    $fieldinfo["name"]["type"]=ASCII;
    $fieldinfo["name"]["size"]=255;
    
    $fieldinfo["ebez"]["type"]=ASCII;
    $fieldinfo["ebez"]["size"]=38;
    
    $fieldinfo["bsart"]["type"]=ASCII;
    $fieldinfo["bsart"]["size"]=16;
    
    $fieldinfo["ynlief"]["type"]=ASCII;
    $fieldinfo["ynlief"]["size"]=8;

    $fieldinfo["zuplatz"]["type"]=ASCII;
    $fieldinfo["zuplatz"]["size"]=15;

    $fieldinfo["abplatz"]["type"]=ASCII;
    $fieldinfo["abplatz"]["size"]=15;

    $fieldinfo["bestand"]["type"]=FLOAT;
    $fieldinfo["bestand"]["size"]=0;

    $fieldinfo["lgbestand"]["type"]=FLOAT;
    $fieldinfo["lgbestand"]["size"]=0;

    $fieldinfo["zbestand"]["type"]=FLOAT;
    $fieldinfo["zbestand"]["size"]=0;

    $fieldinfo["dbestand"]["type"]=FLOAT;
    $fieldinfo["dbestand"]["size"]=0;

    $fieldinfo["lgdbestand"]["type"]=FLOAT;
    $fieldinfo["lgdbestand"]["size"]=0;
    
    $fieldinfo["ve"]["type"]=ASCII;
    $fieldinfo["ve"]["size"]=6;
        
    $fieldinfo["fve"]["type"]=FLOAT;
    $fieldinfo["fve"]["size"]=0;

    $fieldinfo["zeichn"]["type"]=ASCII;
    $fieldinfo["zeichn"]["size"]=22;

    $fieldinfo["lief"]["type"]=ASCII;
    $fieldinfo["lief"]["size"]=8;

    $fieldinfo["lief2"]["type"]=ASCII;
    $fieldinfo["lief2"]["size"]=8;
    
    $fieldinfo["yersteller"]["type"]=ASCII;
    $fieldinfo["yersteller"]["size"]=20;
    
    $fieldinfo["yzeissnr"]["type"]=ASCII;
    $fieldinfo["yzeissnr"]["size"]=15;
    
    $fieldinfo["ypdf1"]["type"]=ASCII;
    $fieldinfo["ydxf"]["type"]=ASCII;    
    $fieldinfo["yxls"]["type"]=ASCII;
    $fieldinfo["ytpdf"]["type"]=ASCII;    
    $fieldinfo["ytlink"]["type"]=ASCII;
    $fieldinfo["bild"]["type"]=ASCII;    
    $fieldinfo["bbesch"]["type"]=ASCII;
    $fieldinfo["foto"]["type"]=ASCII;    
    $fieldinfo["fotoz"]["type"]=ASCII;
    $fieldinfo["catpics"]["type"]=ASCII;    
    $fieldinfo["catpicsz"]["type"]=ASCII;
    $fieldinfo["catpiclz"]["type"]=ASCII;    
    $fieldinfo["caturl"]["type"]=ASCII;        

    $fieldinfo["erfass"]["type"]=ASCII;    
    $fieldinfo["stand"]["type"]=ASCII;     
    $fieldinfo["zeichen"]["type"]=ASCII;     

    $fieldinfo["bem"]["type"]=ASCII;     
    $fieldinfo["kenn"]["type"]=ASCII;     
    $fieldinfo["bstnr"]["type"]=ASCII;     
    $fieldinfo["vbezbspr"]["type"]=ASCII;     
    $fieldinfo["vkbezbspr"]["type"]=ASCII;     
    $fieldinfo["ftext"]["type"]=ASCII;     

    createTable( $table, $new_table_fields, $fieldinfo );

    $copy_fields = array( "nummer", "such", "name", "ebez", 
		     "bsart", "ynlief", "zuplatz", "abplatz", 
		     "bestand", "lgbestand", "zbestand", "dbestand", "lgdbestand", 
		     "ve", "fve", "zeichn", "lief", "lief2", "yersteller", "yzeissnr",
		     "ypdf1", "ydxf", "yxls", "ytpdf", "ytlink", "bild", "bbesch", "foto",
		     "fotoz", "catpics", "catpicsz", "catpiclz", "caturl",
		     "erfass", "stand","zeichen",
		     "bem", "kenn", "bstnr", "vbezbspr", "vkbezbspr", "ftext"
		     
		     );
		     
    $fieldStr = "`".implode( "`,`", $copy_fields )."`";
    
    $sql = "INSERT INTO ".q(DB_ARTICLE)." (".$fieldStr.") ";
    $sql .= "SELECT ".$fieldStr." FROM `Teil:Artikel` WHERE 1";
    
    dbExecute( $sql );
  
  }
  
  // replace all string article numbers by integer (performs much faster)
  function dbCreateProductionList(){
  
 
    $table = DB_PRODUCTION_LIST;
    
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $fields = array( "list_nr", "article_id", "elem_id", "elem_type", "cnt", "tabnr",  );

    $fieldinfo["list_nr"]["type"]=ASCII;
    $fieldinfo["list_nr"]["size"]=15;

    
    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["elem_id"]["type"]=INT;
    $fieldinfo["elem_id"]["size"]=0;

    $fieldinfo["elem_type"]["type"]=INT;
    $fieldinfo["elem_type"]["size"]=0;
    
    $fieldinfo["cnt"]["type"]=FLOAT;
    $fieldinfo["cnt"]["size"]=0;

    $fieldinfo["tabnr"]["type"]=INT;
    $fieldinfo["tabnr"]["size"]=0;
    
    
    createTable( $table, $fields, $fieldinfo );
  

    // create lookup array
    $sql = "SELECT nummer,article_id from ".q(DB_ARTICLE)." WHERE 1";
    $result = dbExecute( $sql );
    
    $articles = array();
    foreach ($result as $item){
      $articles[$item["nummer"]] = $item["article_id"];    
    }
    
    
    // get all entries which need to be copied
    $sql = "SELECT * FROM `Fertigungsliste:Fertigungsliste` WHERE 1 ";
    $result = dbExecute($sql);
      
    $dataSet = array();
    
    foreach ($result as $item){
      
      // get article id
      $abas_nr = $item["artikel"];
      if (isset($articles[$abas_nr])){
	$article_id = $articles[$abas_nr];
      } else {
	$article_id = -2;
      }
      
      // get element id
      $abas_nr = $item["elem"];
      if (isset($articles[$abas_nr])){
	$elem_id = $articles[$abas_nr];
      } else {
	$elem_id = -2;
      }
      
      // if article is not of article type
      if ($item["elart"] != 1){
	// 
	$elem_id = -1;
      }
      
      $values = array( $item["nummer"], $article_id, $elem_id, $item["elart"], $item["anzahl"], $item["tabnr"] );
	
      $dataSet[] = $values;
      
    }
    lg( "inserting into table now " );
    
    insertIntoTable( $table, $fields, $dataSet );
    
  }

  // replace all string article numbers by integer (performs much faster)
  function dbCreateProductionList_old(){
  
 
    $table = DB_PRODUCTION_LIST;
    
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $fields = array( "list_nr", "article_id", "elem_id", "elem_type", "cnt", "tabnr",  );

    $fieldinfo["list_nr"]["type"]=ASCII;
    $fieldinfo["list_nr"]["size"]=15;

    
    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["elem_id"]["type"]=INT;
    $fieldinfo["elem_id"]["size"]=0;

    $fieldinfo["elem_type"]["type"]=INT;
    $fieldinfo["elem_type"]["size"]=0;
    
    $fieldinfo["cnt"]["type"]=FLOAT;
    $fieldinfo["cnt"]["size"]=0;

    $fieldinfo["tabnr"]["type"]=INT;
    $fieldinfo["tabnr"]["size"]=0;
    
    
    createTable( $table, $fields, $fieldinfo );

    // prepare insert fields
    $sel_fields = array(  "list_nr", "article_id", "elem_id", "elem_type", "cnt", "tabnr"  );
    $fieldStr = "`".implode( "`,`", $sel_fields )."`";
   
    // prepare insert query
    $sql_insert = "INSERT INTO ".q(DB_PRODUCTION_LIST)." (".$fieldStr.") VALUES ";
    
    // get all entries which need to be copied
    $sql = "SELECT * FROM `Fertigungsliste:Fertigungsliste` WHERE 1 ";
    $result = dbExecute($sql);
    
    foreach ($result as $item){
    
      $sql = "SELECT article_id FROM ".q(DB_ARTICLE)." WHERE nummer='".$item["artikel"]."'";
      $q = dbExecute( $sql );
      $article = $q->fetch();
      $article_id = $article["article_id"];
	  
      if ($item["elart"] == 1){
	$sql = "SELECT article_id FROM ".q(DB_ARTICLE)." WHERE nummer='".$item["elem"]."'";
	$q = dbExecute( $sql );
	$elem = $q->fetch();
	$elem_id = $elem["article_id"];
      } else {
	$elem_id = -1;
      }
      
      $values = array( $item["nummer"], $article_id, $elem_id, $item["elart"], $item["anzahl"], $item["tabnr"] );
      $valueStr = "('".implode( "','", $values )."')";
      
      dbExecute( $sql_insert.$valueStr );
      
      
    }
  
  }
  
  
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
  
  function dbCreateOrders(){
     
      $table = DB_ORDERS;
      
      if (tableExists( $table )== true ){
         removeTable ($table );
      }
      $fields = array("artikel_id", "nummer", "betreff", "aumge", "planmge", "such", );
      
      $fieldinfo["artikel_id"]["type"]=INDEX;
      $fieldinfo["artikel_id"]["size"]=0;
      
      $fieldinfo["nummer"]["type"]=ASCII;
      $fieldinfo["nummer"]["size"]=15;
      
      $fieldinfo["betreff"]["type"]=ASCII;
      $fieldinfo["betreff"]["size"]=15;
      
      $fieldinfo["aumge"]["type"]=FLOAT;
      $fieldinfo["aumge"]["size"]=0;
      
      $fieldinfo["planmge"]["type"]=FLOAT;
      $fieldinfo["planmge"]["size"]=0;
      
      $fieldinfo["such"]["type"]=ASCII;
      $fieldinfo["such"]["size"]=30;
         
      createTable( $table, $fields, $fieldinfo );
      
       // create lookup array
    $sql = "SELECT nummer,article_id from ".q(DB_ARTICLE)." WHERE 1";
    $result = dbExecute( $sql );
    
    $articles = array();
    foreach ($result as $item){
      $articles[$item["nummer"]] = $item["article_id"];    
    }
    
    
    // get all entries which need to be copied
    $sql = "SELECT * FROM `Einkauf:Bestellung` WHERE 1 ";
    $result = dbExecute($sql);
      
    
    $dataSet = array();
    
    foreach ($result as $item){
      
      // get article id
      $abas_nr = $item["artikel"];
      if (isset($articles[$abas_nr])){
	$article_id = $articles[$abas_nr];
      } else {
	$article_id = -2;
      }
                
      $values = array( $article_id, $item["nummer"], $item["betreff"], $item["aumge"], $item["planmge"], $item["such"],  );
	
      $dataSet[] = $values;
      
    }
    lg( "inserting into table now " );
    
    insertIntoTable( $table, $fields, $dataSet );
  }
  
    // replace all string article numbers by integer (performs much faster)
  function dbCreateOrders_old(){
  
 
     $table = DB_ORDERS;
        
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $fields = array("artikel_id", "nummer", "betreff", "aumge", "planmge", "such", );

      $fieldinfo["artikel_id"]["type"]=INDEX;
      $fieldinfo["artikel_id"]["size"]=0;
      
      $fieldinfo["nummer"]["type"]=ASCII;
      $fieldinfo["nummer"]["size"]=15;
      
      $fieldinfo["betreff"]["type"]=ASCII;
      $fieldinfo["betreff"]["size"]=15;
      
      $fieldinfo["aumge"]["type"]=FLOAT;
      $fieldinfo["aumge"]["size"]=0;
      
      $fieldinfo["planmge"]["type"]=FLOAT;
      $fieldinfo["planmge"]["size"]=0;
    
      $fieldinfo["such"]["type"]=ASCII;
      $fieldinfo["such"]["size"]=30;
    
    createTable( $table, $fields, $fieldinfo );

    // prepare insert fields
    $sel_fields = array(  "artikel_id", "nummer", "betreff", "aumge", "planmge", "such", );
    $fieldStr = "`".implode( "`,`", $sel_fields )."`";
   
    // prepare insert query
    $sql_insert = "INSERT INTO ".q(DB_ORDERS)." (".$fieldStr.") VALUES ";
    
    // get all entries which need to be copied
    $sql = "SELECT * FROM `Einkauf:Bestellung` WHERE 1 ";
    $result = dbExecute($sql);
    
    foreach ($result as $item){
    
      $sql = "SELECT article_id FROM ".q(DB_ARTICLE)." WHERE nummer='".$item["artikel"]."'";
      $q = dbExecute( $sql );
      $article = $q->fetch();
      $article_id = $article["article_id"];
	        
      $values = array(  $article_id, $item["nummer"], $item["betreff"], $item["aumge"], $item["planmge"], $item["such"],  );
      $valueStr = "('".implode( "','", $values )."')";
      
      dbExecute( $sql_insert.$valueStr );           
    }      
 
  }
  
 function prepareDatabase(){
    
    dbCreateTableArticle();
    dbCreateProductionList();
    dbCreateDict();
    dbCreateRank();
    dbCreateOrders();
   
  }
 
  
  ?>
