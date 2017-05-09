<?php


  /*
   * the production-list from EDP is basically a two colum list
   * with the columns "owner" and "sub-component".
   *
   * we need to replace all string article numbers by integer (performs much faster)
   * 
   */
  function dbCreateProductionList(){
  
 
    $table = DB_PRODUCTION_LIST;
    backTrace("dbCreateProductionList");
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $fields = array( "list_nr", "article_id", "elem_id", "elem_type", "cnt", "tabnr",  );

    $fieldinfo=array();
    
    $fieldinfo["list_nr"]["type"]=INT;
    $fieldinfo["list_nr"]["size"]=0;

    
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
  

    // create a lookup array with all article numbers
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
    
    // we prepare one really big table here
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
    lg( "inserting into table ".$table." now " );
    
    // finally we put the big table into our database
    insertIntoTable( $table, $fields, $dataSet );
    
    report("imported ".count($dataSet)." items of productions list successfully");
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
  
  ?>