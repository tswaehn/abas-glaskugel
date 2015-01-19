<?php


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
  
?>