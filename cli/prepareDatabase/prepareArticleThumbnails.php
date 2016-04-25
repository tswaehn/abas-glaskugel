<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include( '../article/renderMedia.php');
/*
 *  we expect a ready imported article database
 * 
 *  this function 
 *  (1) loads all articles
 *  (2) for each article create a thumbnail
 *  (3) store the location back into article database
 * 
 */
function dbCreateArticleThumbnails(){
  
  // get all articles
  $sql = "SELECT * FROM `".DB_ARTICLE."` WHERE 1 ";
  $result = dbExecute($sql);  

  // go through each of them
  $updateArray= array();
  $count= 0;
  foreach ($result as $article){

    $count++;
    if ($count >=10){
      break;
    }
    // need the article ID
    $articleID= $article["article_id"];

    // set thumbnail directory
    $filename= "../article/cache/thumb--".$articleID."jpg";
    // convert
    $media= filterValidMedia( $article );
    print_r( $media );
    
    cacheThumbnail( $media, $filename, 80, 80 );    


    // prepare the item for the database
    $item["col"]= "thumbnail";
    $item["val"]= $filename;
    $item["whereCol"]= "article_id";
    $item["whereVal"]= $articleID;

    // add to update array
    $updateArray[]= $item;
  }  
  
  // finally write the array to the table
  updateTable( DB_ARTICLE, $updateArray );
  
}
