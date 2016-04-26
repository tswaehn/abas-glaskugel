<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define ("CACHE_FOLDER", "../article/cache/");

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

    // need the article ID
    $articleID= $article["article_id"];

    // set thumbnail directory
    $filename= "thumb-".$articleID.".jpg";
    // convert
    $media= filterValidMedia( $article );
    //print_r( $media );
    if ($count >=1000){
      $count= 0;
      lg( date("r") );
      //continue;
    }
    
    cacheThumbnail( $media, CACHE_FOLDER.$filename, 320, 240 );    


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

// ----------------

  $mediaFields = array("ypdf1", "ydxf", "yxls", "ytpdf", "ytlink", "bild",
		  "bbesch", "foto", "fotoz", "catpics", "catpicsz", 
		  "catpicl", "catpiclz", "caturl" );

  $mediaIgnore = array("W:\DXF\\", "W:\Bilder\\", "W:\PDF\\", "W:\Doku\\", "W:\Datenblaetter\\", "WWW.", "W:\\", "" );
  
  $mediaThumbnail = array( "png", "jpg", "jpeg", "gif", "tif" );
  
  function filterValidMedia( $article ){
  
    global $mediaFields;
    global $mediaIgnore;

    $media = array();
    
    foreach ($mediaFields as $field ){
      if (isset( $article[$field] )){
	$name = $article[$field];
	$ignore=0;
	foreach ($mediaIgnore as $ignore){
	  if (strcasecmp( $name, $ignore ) == 0){
	    $ignore=1;
	    break;
	  }
	}
	
	if ($ignore==0){
	  // replace mapped drive by unc
	  //$name=str_ireplace("W:\\", "\\\\HSEB-SV2\\Daten\\", $name);
	  $name=str_ireplace("W:\\", "\\\\192.168.0.241\\Daten\\", $name);
	  $media[] = $name;
	}
	
      }
    }
    
    // remove duplicate items
    $media = array_unique ( $media );
    
    return $media;
  }

  function dir_contents_recursive($dir, &$result=array() ) {
      // open handler for the directory
      $iter = new DirectoryIterator(  utf8_decode( $dir ) ); // php file access is always ISO-8859-1 

      foreach( $iter as $item ) {
	  // make sure you don't try to access the current dir or the parent
	  if ($item != '.' && $item != '..') {
		  if( $item->isDir() ) {
			  // call the function on the folder
			  dir_contents_recursive("$dir/$item", $result);
		  } else {
			  // print files
			  $file =  $dir . "/" .utf8_encode( $item->getFilename() );
			  $result[] = $file;
		  }
	  }
      }
      
      return $result;
  }

  
  
  
  function cacheThumbnail( $media, $targetFile, $width=800, $height=600 ){
    global $mediaThumbnail;
    //$max_width=500;
    //$min_width=40;

    foreach ($media as $item ){
      // php file access is always ISO-8859-1 
      if (is_dir( utf8_decode( $item))){
	$result=dir_contents_recursive( $item );
	foreach ($result as $newitem){
	  $media[] = $newitem;
	}
      }
    }

    $images = array();
    
    foreach ($media as $item ){
      
      if (is_file( utf8_decode( $item ))){ // php file access is always ISO-8859-1 
	$info = pathinfo( $item );
	
	if (isset($info["extension"])){
	  $ext = $info["extension"];
	  
	  if (in_arrayi( $ext, $mediaThumbnail)){
	    $images[] = $item;
	  }
	}
      }
    }
    
    // unique
    $images = array_unique( $images );
    
    $count=sizeof($images);
    if (($count > 0) && (is_file($images[0]))){
      
      // copy - paste to cache
      // take always the first image
      $image= $images[0];

      lg( "taking ".$image );
      
		  $img = new imagick(); // [0] can be used to set page number
		  $img->setResolution(90,90);
		  //$img->setSize(800,600);
		  $img->readImage($image );
		  $img->setImageFormat( "jpeg" );
		  $img->setImageCompression(imagick::COMPRESSION_JPEG); 
		  $img->setImageCompressionQuality(90); 
		  $img->scaleImage($width, $height,true);

		  $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

		  $img->writeimage( $targetFile );
	  
    } else {
	
      $found_something_to_display=0;
      // check for an pdf to display
      foreach ($media as $item){
	$info = pathinfo($item, PATHINFO_EXTENSION);
	if (in_arrayi( $info, array("pdf"))){
	  echo "converting pdf2jpg";
	  
	  if (is_file($item)){
		$found_something_to_display=1;

	      $img = new imagick(); // [0] can be used to set page number
	      $img->setResolution(90,90);
	      $img->readImage($item.'[0]');
              

              $img->setImageFormat( "jpeg" );
	      $img->setImageCompression(imagick::COMPRESSION_JPEG); 
	      $img->setImageCompressionQuality(90); 

	      //$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
	      
	      //$img->__toString();
	      $img = $img->flattenImages();
              $img->resizeimage($width, $height, Imagick::FILTER_LANCZOS, 0.9, true);
	      //$img->writeImage('./pageone.jpg'); 
              $img->writeimage( $targetFile );
          
		break;
	  }
	}	
      }
      
      if ($found_something_to_display==0){
	$file_link="../article/image_placeholder.png";
        
        $img = new imagick(); // [0] can be used to set page number
        $img->setResolution(90,90);
        $img->readImage($file_link );
        $img->setImageFormat( "jpeg" );
        $img->setImageCompression(imagick::COMPRESSION_JPEG); 
        $img->setImageCompressionQuality(90); 
		$img->scaleImage( $width, $height,true);

        $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

        $img->writeimage( $targetFile );
        
      }
    }

  }
