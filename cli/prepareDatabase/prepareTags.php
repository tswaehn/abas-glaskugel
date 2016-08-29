<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  $superSearchTags= array(  
      "bg", "kabel", "schwarz", "steuerkabel", "ul", "rohs","rk", "profil", 
      "natur", "zuschnitt", "blech", "platte", "stecker", "buchse", "winkel",
      "phoenix", "sensor", "kaufkabel", "adapter", "braun", "blau", "schraube",
      "grau", "netzkabel", "odin", "leiterplatte", "controller", "rolle", "aluprofil",
      "kamera", "aderend", "gerade", "motor", "2000", "1000", "500", "301", "300", "200", "100", "1mm", "300v", "90", "6mm", "axiospect", "axiotron",
      "albatros", "patchkabel", "gestell", "haube", "netzteil", "komplett", "dedo", "filter",
      "pvc", "kabelsatz", "wafer", "set", "deckel", "schild", "stage", "usb", "lpm", "din912", 
      "edelstahl", "scheibe", "gehäuse", "rund", "objektiv", "abdeckung", "dc","bedienpult", "endeffektor",
      "kassette", "linse", "mini", "dunkelfeld", "gewindestift", "oben", "schleuse", "monitor", "alu", "pa",
      "spiegel", "kunststoff", "lampe", "mima", "auflage", "dimmbox", "hub", "epi", "backside", "klotz",
      "rack", "blu", "schlauch", "emo", "klemme", "lochblech", "bolzen", "selbstklebend", "aufnahme", "tragschiene",
      "target", "stange", "zylinderstift", "bnc", "niro", "reflektor", "apex", "bearbeitet", "lim", "eslon",
      "anbau", "führung", "steckerplatte", "lic", "rechte", "träger", "streuscheibe", "hülse","ungelocht",
      "flach", "ebene", "einsatz", "ns3515", "basisplatte", "glas", "kappe", "labmic","transportsicherung",
      "dichtung", "fixierung", "verpackung", "groß", "holzkiste", "auflagebolzen", "rg174",
      "m5", "m3", "m4"
      
      );
  
  /*
   * insert a groups defined by an array
   *    array( groupA => array( tag1, tag2, ...), groupB => array( tag3, tag2, ...) )
   * 
   * into database and return the suiting tagID for latter use of tagging
   * 
   * return:
   *      array of groupName=> tagName => tagID
   */
  function addGroupsAndTagsToDB( $groups ){
    
    // convert groups into dataSet
    $dataSet= array();
    foreach ($groups as $group=>$tagNameArray){
      foreach ($tagNameArray as $tagName){
        $dataSet[]= array( 0, $group, $tagName );
      }
    }
    
    // insert groups into table
    $fields = array( "tag", "group_name", "tag_name" );
    insertIntoTable( DB_ARTICLE_GROUPS, $fields, $dataSet );
    
    
    // 3. get correct tag IDs and tag names => create array with GroupName=>TagName=>Tag
    $result= dbGetFromTable(DB_ARTICLE_GROUPS, array("tag", "group_name", "tag_name"), "" , 100000 );
    $tags= array();
    foreach ($result as $item){
      $tag= $item["tag"];
      $groupName= $item["group_name"];
      $tagName= $item["tag_name"];
      $tags[$groupName][$tagName]= $tag;
    }
    
    return $tags;
  }
  
  /*
   * go in these steps
   *   1. get the unique groups that need be associated
   *   2. insert groups into group table
   *   3. get the correct tag IDs and tag names
   *   4. get all articles and attach correct group-tag-id
   *   5. store into tag table
   * 
   */
  function fill_beschaffungsart(){
    
    // 1. get groups
    $sql= "SELECT `article_id`,`bsart` FROM `gk_article` WHERE 1 GROUP BY `bsart`";
    $result= dbExecute($sql);
    
    $dataSet= array();
    foreach ($result as $item ){
      $tagName= $item["bsart"];
      $dataSet[]= array( 0, "Beschaffung", $tagName );
    }
    
    // 2. insert groups into table
    $fields = array( "tag", "group_name", "tag_name" );
    insertIntoTable( DB_ARTICLE_GROUPS, $fields, $dataSet );
    
    // 3. get correct tag IDs and tag names => create array with TagName=>Tag
    $result= dbGetFromTable(DB_ARTICLE_GROUPS, array("tag", "tag_name"), "" , 100000 );
    $tags= array();
    foreach ($result as $item){
      $tag= $item["tag"];
      $tagName= $item["tag_name"];
      $tags[$tagName]= $tag;
    }
    
    // 4. get all articles and tag them
    $articleFields = array( "article_id", "bsart" );    
    $result = dbGetFromTable( DB_ARTICLE, $articleFields, "", 100000 );
    $count = $result->rowCount();
    // start with empty dataset
    $dataSet= array();
    foreach( $result as $item ){
      
      $article_id = $item["article_id"];
      $tag= $tags[ $item["bsart"] ];
      
      $dataSet[]= array(0, $article_id, $tag );
      
    }
    
    // 5. finally write each single (str,article_id,frequency)-pair to database (including reference to article)
    $fields = array( "tid", "article_id", "tag" );
    insertIntoTable( DB_ARTICLE_TAGS, $fields, $dataSet );
    
  }
  
  /*
   * go in these steps
   *   1. get the unique groups that need be associated
   *   2. insert groups into group table
   *   3. get the correct tag IDs and tag names
   *   4. get all articles and attach correct group-tag-id
   *   5. store into tag table
   * 
   */
  function fill_serviceParts(){
    
    // 1. get groups
    $sql= "SELECT `article_id`,`ersatzt` FROM `gk_article` WHERE 1 GROUP BY `ersatzt`";
    $result= dbExecute($sql);
    
    $dataSet= array();
    foreach ($result as $item ){
      $tagName= $item["ersatzt"];
      $dataSet[]= array( 0, "Ersatzteil", $tagName );
    }
    
    // 2. insert groups into table
    $fields = array( "tag", "group_name", "tag_name" );
    insertIntoTable( DB_ARTICLE_GROUPS, $fields, $dataSet );
    
    // 3. get correct tag IDs and tag names => create array with TagName=>Tag
    $result= dbGetFromTable(DB_ARTICLE_GROUPS, array("tag", "tag_name"), "" , 100000 );
    $tags= array();
    foreach ($result as $item){
      $tag= $item["tag"];
      $tagName= $item["tag_name"];
      $tags[$tagName]= $tag;
    }
    
    // 4. get all articles and tag them
    $articleFields = array( "article_id", "ersatzt" );    
    $result = dbGetFromTable( DB_ARTICLE, $articleFields, "", 100000 );
    $count = $result->rowCount();
    // start with empty dataset
    $dataSet= array();
    foreach( $result as $item ){
      
      $article_id = $item["article_id"];
      $tag= $tags[ $item["ersatzt"] ];
      
      $dataSet[]= array(0, $article_id, $tag );
      
    }
    
    // 5. finally write each single (str,article_id,frequency)-pair to database (including reference to article)
    $fields = array( "tid", "article_id", "tag" );
    insertIntoTable( DB_ARTICLE_TAGS, $fields, $dataSet );
    
  }
  
  
  function getTagsFromFreeText( $ftext ){
   
    echo "--\n".$ftext."\n";
    $lines= preg_split( "/\[-10-\]/", $ftext );
    //print_r($lines);

    $result= array();
    foreach ($lines as $line){
      $hits= array();
      $test= preg_match_all( "/\[(.*)\](.*)/", $line, $hits);

      if ($test != 0){
        //print_r($hits);
        $result[ $hits[1][0] ][]= $hits[2][0];
      }
    }
    
    if (!empty($result)){
      print_r( $result );
    } else {
      $result= NULL;
    }
    return $result;   
  }
  
  
/*
   * go in these steps
   *   1. get the unique groups that need be associated
   *   2. insert groups into group table
   *   3. get the correct tag IDs and tag names
   *   4. get all articles and attach correct group-tag-id
   *   5. store into tag table
   * 
   */
  function fill_serviceTags(){
    
    $validGroups= array("ServiceTag" );
    
    // 1. get groups
    $sql= "SELECT `article_id`,`ftext` FROM `gk_article` WHERE `ftext`<>''";
    $result= dbExecute($sql);
    
    $articles= array();
    $groups= array();
    $match= "/".implode("|",$validGroups)."/i";
    
    // go through all found articles that have some text in "T1" - free text field
    foreach ($result as $item ){
      $article_id= $item["article_id"];
      $ftext= $item["ftext"];
      
      // split full text into "[EtiLi]" or "[ServiceTag-Axiospect]", ...
      $serviceTags= getTagsFromFreeText($ftext);
      
      // add found tags to groups
      if (is_array($serviceTags)){
        foreach ($serviceTags as $key=>$serviceTagArray ){
          // compare found key to valid keys
          if (preg_match( $match, $key)){
            // add each single tag for that key==group
            foreach ($serviceTagArray as $serviceTag){
              // remove spaces
              $serviceTag= trim($serviceTag);
              // if non-empty actually add
              if (!empty($serviceTag)){
                // add to group
                $groups[$key][]= $serviceTag;
                // add to article
                $articles[$article_id][$key][]= $serviceTag;
              }
            }
          }
        }
      }
    } // end going through all articles
    
    // make all tags unique
    foreach ($groups as $group=>$serviceTags){
      $groups[$group]= array_unique($serviceTags);
    }
    
    $groupTags= addGroupsAndTagsToDB( $groups );
    
    print_r($articles);
    
    // go through all articles and attach the correct tagID
    $dataSet= array();
    foreach ($articles as $article_id=>$groups ){
      foreach( $groups as $groupName=>$tagNameArray ){
        foreach( $tagNameArray as $tagName ){
          // lookup tagID
          $tagID= $groupTags[$groupName][$tagName];
          // add to array
          $dataSet[]= array(0, $article_id, $tagID );
        }
      }
    }
    
    /*
    // 4. get all articles and tag them
    $articleFields = array( "article_id", "ersatzt" );    
    $result = dbGetFromTable( DB_ARTICLE, $articleFields, "", 100000 );
    $count = $result->rowCount();
    // start with empty dataset
    $dataSet= array();
    foreach( $result as $item ){
      
      $article_id = $item["article_id"];
      $tag= $tags[ $item["ersatzt"] ];
      
      $dataSet[]= array(0, $article_id, $tag );
      
    }
*/    
    // 5. finally write each single (str,article_id,frequency)-pair to database (including reference to article)
    $fields = array( "tid", "article_id", "tag" );
    insertIntoTable( DB_ARTICLE_TAGS, $fields, $dataSet );
  
  }
  
  
  function dbCreateTableTags(){

    $table = DB_ARTICLE_TAGS;

    if (tableExists( $table ) == true ){
      removeTable( $table );
    }

    $new_table_fields = array( "tid", "article_id", "tag" );

    $fieldinfo=array();

    $fieldinfo["tid"]["type"]=INDEX;
    $fieldinfo["tid"]["size"]=0;

    $fieldinfo["article_id"]["type"]=INT;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["tag"]["type"]=INT;
    $fieldinfo["tag"]["size"]=0;

    createTable( $table, $new_table_fields, $fieldinfo );    

 }
 

  function dbCreateTableGroups(){

    $table = DB_ARTICLE_GROUPS;

    if (tableExists( $table ) == true ){
      removeTable( $table );
    }

    $new_table_fields = array( "tag", "group_name", "tag_name" );

    $fieldinfo=array();

    $fieldinfo["tag"]["type"]=INDEX;
    $fieldinfo["tag"]["size"]=0;

    $fieldinfo["group_name"]["type"]=ASCII;
    $fieldinfo["group_name"]["size"]=50;

    $fieldinfo["tag_name"]["type"]=ASCII;
    $fieldinfo["tag_name"]["size"]=50;

    createTable( $table, $new_table_fields, $fieldinfo );    

 }
         
  function dbCreateTags(){
   
    backTrace("dbCreateTags");
    
    dbCreateTableTags();
    dbCreateTableGroups();
   
   
    // 
    fill_beschaffungsart();   
    //
    fill_serviceParts();
    //
    fill_serviceTags();
 }