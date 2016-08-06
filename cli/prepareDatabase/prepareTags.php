<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
   
 }