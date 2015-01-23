<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
   * We assume there is a table called "Teil:Artikel"
   * now we take all "interesting" columns an re-order/import
   * them to our working table. The final tablename is defined
   * by DB_ARTICLE. 
   *
   */
  function dbCreateTableStoragePlaces(){
  
    $table = DB_STORAGE;
    
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $new_table_fields = array( "storage_id", "article_id", "lemge", "such", "name", "lgruppe", "lager", "dispo" );
        
    $fieldinfo=array();

    $fieldinfo["storage_id"]["type"]=INDEX;
    $fieldinfo["storage_id"]["size"]=0;
    
    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["lemge"]["type"]=FLOAT;
    $fieldinfo["lemge"]["size"]=0;

    $fieldinfo["such"]["type"]=ASCII;
    $fieldinfo["such"]["size"]=15;
    
    $fieldinfo["name"]["type"]=ASCII;
    $fieldinfo["name"]["size"]=15;

    $fieldinfo["lgruppe"]["type"]=ASCII;
    $fieldinfo["lgruppe"]["size"]=15;

    $fieldinfo["lager"]["type"]=ASCII;
    $fieldinfo["lager"]["size"]=15;
    
    $fieldinfo["dispo"]["type"]=ASCII;
    $fieldinfo["dispo"]["size"]=5;
        

    createTable( $table, $new_table_fields, $fieldinfo );

    // create a lookup array with all article numbers
    $sql = "SELECT nummer,article_id from ".q(DB_ARTICLE)." WHERE 1";
    $result = dbExecute( $sql );
    $articles = array();
    foreach ($result as $item){
      $articles[$item["nummer"]] = $item["article_id"];    
    }

    // create a lookup array with storage groups
    $sql = "SELECT nummer,such from `lager:lagergruppe` WHERE 1";
    $result = dbExecute( $sql );
    $groups = array();
    foreach ($result as $item){
      $groups[$item["nummer"]] = $item["such"];    
    }
    
    // create a lookup array with storage types
    $sql = "SELECT nummer,such from `lager:lager` WHERE 1";
    $result = dbExecute( $sql );
    $types = array();
    foreach ($result as $item){
      $types[$item["nummer"]] = $item["such"];    
    }
    
    // create a lookup array with all storate paces
    $sql = "SELECT nummer,such,name,lager,lgruppe,dispo from `lplatz:lagerplatzkopf` WHERE 1";
    $result = dbExecute( $sql );
    $lagerplatz = array();
    foreach ($result as $item){
      
      $lagerplatz[$item["nummer"]] = array( $item["such"],
                                            $item["name"], 
                                            $groups[ $item["lgruppe"] ], 
                                            $types[ $item["lager"] ], 
                                            $item["dispo"] );    
    }

    // copy values from table to glaskugel
    $sql = "SELECT artikel,lemge,platz from `lmenge:lagermenge` WHERE 1";
    $result = dbExecute( $sql );
    $dataSet=array();
    foreach ($result as $item){
      // lookup article ID
      $articleID= $articles[ $item["artikel"]];
      // take count
      $lemge= $item["lemge"];
      // take place
      $platzNummer= $item["platz"];
      // lookup place info
      $platz= $lagerplatz[$platzNummer];

      // create single data pair
      $values= array_merge( array("", $articleID, $lemge), $platz);
      
      lg(">". implode(" ",$values));
      // add to list of data pairs
      $dataSet[]= $values;
    }
    
    lg( "inserting into table now " );
    
    // finally we put the big table into our database
    insertIntoTable( $table, $new_table_fields, $dataSet );
    
  }

?>
