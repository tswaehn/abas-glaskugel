<?php
  
  $dict_ignore= array( 
            "00", "schillhahn", "hseb", "0", "1", "sinkwitz", "2", "01", "3", "mit", 
            "hebenstreit", "4", "für", "5", "zeichnung", "02", "rs", "6", "s", "8", "10", "sonder", "zimmer", "ohne", "a2",
            "version", "7", "in", "m", "flon", "9", "f", "und", "03", "d", "niedballa", "hseb01", "oz", "kauf", "r",
            "voigt", "farnell", "raue", "kleisz", "metrofunk", "vk", "lang", "12", "20", "zaspel", "dell", "äz0",
            "srocka", "04", "15", "bearb", "30", "äz01", "zeiss", "igus", "dsub", "rj45", "eis", "ring", "bis",
            "änd", "v2", "naumann", "für", "äz02", "typ", "alt", "2009", "AEZ00", "lapp", "nr.", "neu",
            "äz", "bearb.", "dresden", "ab", "achtung", "möglich", "universal", "material", "schroff",
            "gesamt", "allgemein", "kalkulation", "neuem"
            
      
  );
  
  // unklar
  /*
   * zu 6kt shr led pc aus 4.015000 pl li power rechts front 24 links light re uni hr hf whm us hz may snr gross
   * 
   * mc1.5
   */
  $synonyme= array(
      "schwarz"=> array("schwarz", "sw"),
      "rot"=> array("rot", "rt"),
      "orange"=> array("orange", "or"),
      "gelb"=> array("gelb", "ge"),
      "grün" => array("grün", "gr", "gruen"),
      "buchse"=> array("buchse", "bu"),
      "stecker"=> array("stecker", "st"),
      "schalter"=> array("schalter", "switch"),
      "braun"=> array("braun", "bn"),
      "blau"=> array("blau", "bl"),
      "schraube"=> array("schraube", "innensechskant", "zylinderschraube"),
      "winkel"=> array("halter", "winkel"),
      "leiterplatte"=> array("leiterplatte", "lp", "board", "if", "interface"),
      "elektro"=> array("elektro", "elt"),
      "aluprofil"=> array("profil", "40x40", "30x30", "20x20"),
      "führung"=> array("führung", "fuehrung"),
      "rm381"=> array("rm381", "3.81"),
      "5m" => array("5m", "5000mm"),
      "3m" => array("3m", "3000mm"),
      "2m" => array("2m", "2000mm"),
      "1.8m" => array("1.8m"),
      "kabel" => array("kabel", "cable", "schaltgeräteleitung"),
      "awg"=> array("2xawg18"),
      "netzteil" => array("netzteil"),
      "set"=> array("set", "kit"),
      "platte"=> array("platte", "grundplatte", "platte2"),
      "gehäuse"=> array("gehäuse", "gehaeuse"),
      "dsub"=> array("dsub", "hd", "dsub15" ),
      "kamera"=> array("kamera", "camera"),
      "aderend"=> array("aderend", "löt", "crimp"),
      "klemme"=> array("klemme", "kl"),
      "modul"=> array("modul", "module"),
      "objektiv"=> array("objektiv", "objective", "obj"),
      "reflektor"=> array("reflektor", "reflector", "refl"),
      "dunkelfeld"=> array("dunkelfeld", "df"),
      "lampe"=> array("lampe", "lamp"),
      "backside"=> array("backside", "bs"),
      "bolzen"=> array("bolzen", "abstandsbolzen" ),
      "endeffektor"=> array("endeffektor", "eef"),
      "m4"=> array("m4", "m4x8"),
      "blech"=> array("blech", "blech3"),
      "groß"=> array("groß", "gross")
      
      
  );
  
  function dbCreateTableDict(){
    
    $table = DB_DICT;
  
    $fields = array( "id", "str", "article_id", "frequency" );
    
    $fieldinfo=array();
    $fieldinfo["id"]["type"]=INDEX;
    $fieldinfo["id"]["size"]=0;
    $fieldinfo["str"]["type"]=ASCII;
    $fieldinfo["str"]["size"]=30;
    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;
    $fieldinfo["frequency"]["type"]=INT;
    $fieldinfo["frequency"]["size"]=0;
    

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

/*
  function dbAddToDict( $article_id, $values ){
    global $pdo;

    $fields = array( "str", "article_id" );
    foreach ($values as $value){
	insertIntoTable( DB_DICT, $fields, array( array( $value, $article_id )) );
    }
  }
  */
  function dbCreateDict(){

    backTrace("dbCreateDict");
    
    lg( 'creating dict ' );
    $starttime = microtime(true); 
    
    dbCreateTableDict();
    
    //$fields = array( "article_id", "nummer", "such", "name", "ebez", "bsart", "ynlief","zeichn","lief","lief2","yersteller","yzeissnr"  );
    $articleFields = array( "article_id", "nummer", "such", "name", "ebez", "ynlief","zeichn","yersteller","yzeissnr"  );    
    
    $result = dbGetFromTable( DB_ARTICLE, $articleFields, "", 100000 );

    $count = $result->rowCount();
    
    $i=0;
    $k=0;
    
    $outputCount=10;
    
    $dataSet = array();
    $frequency= array();
    
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
      
      foreach ($dict as $str){
        // add to set
	$dataSet[] = array( $str, $article_id,0);   
        
        // count occurences of strings
        if (isset($frequency[$str])){
          // existing entry
          $frequency[$str]++;
        } else {
          // new entry
          $frequency[$str]=1;
        }
      }
    
    }

    $rank= array();
    // attach to each existing (str,article)-pair the appropriate frequency
    foreach ($dataSet as $key=>$data){
      // load dataSet line values
      $str= $data[0];
      $article_id= $data[1];
      // lookup frequency
      $freq= $frequency[$str];
      // write back
      $dataSet[$key][2]= $freq;
      // sum up article rank
      if (isset($rank[$article_id])){
        // add count to existing 
        $rank[$article_id] += $freq;
      } else {
        // create new rank entry
        $rank[$article_id]= $freq;
      }
    }
    
    // set rank for each single article_id
    $values= array();
    foreach ($rank as $article_id=>$freq){
      $item["col"]= "rank";
      $item["val"]= $freq;
      $item["whereCol"]= "article_id";
      $item["whereVal"]= $article_id;
      $values[]= $item;
    } 
    updateTable( DB_ARTICLE, $values);
    
    // finally write each single (str,article_id,frequency)-pair to database (including reference to article)
    $fields = array( "str", "article_id", "frequency" );
    insertIntoTable( DB_DICT, $fields, $dataSet );
    
    
    
    $endtime = microtime(true); 
    $timediff = $endtime-$starttime;
    debug( '\n exec time is '.($timediff) );    
    
    report( "found ".count($frequency)." different words for my search dictionary");
  }
  


   
 ?>
