<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define ("CACHE_FOLDER", "../pages/article/cache/");


// ----------------
  
  // definition for accepted fields for images/pdfs/drawings
  $mediaFields = array("bild",
		  "bbesch", "foto", "fotoz", "catpics", "catpicsz", 
		  "catpicl", "catpiclz", "caturl",
                  "ypdf1", "ydxf", "yxls", "ytpdf", "ytlink" );

  // value fields that need to be skipped as these are some default values only
  $mediaIgnore = array("W:\DXF\\", "W:\Bilder\\", "W:\PDF\\", "W:\Doku\\", "W:\Datenblaetter\\", "W:\XLS\\", "WWW.", "W:\\", "W:", "",
					   "W:\DXF", "W:\Bilder", "W:\PDF", "W:\DOKU", "W:\Datenblaetter", "W:\XLS");
  
  // media of first choice that is usable as thumbnail
  $mediaThumbnail = array( "png", "jpg", "jpeg", "gif", "tif", "pdf" );
    
  $failedMediaLinks= 0;
  $cachedMediaToday= 0;
  $articlesWithoutThumbnails= 0;
  
  /*
   * check if an url exists
   */
  function url_exists($url) {
    if (!$fp = curl_init($url)){
      return false;
    } else {
      return true;
    }
  }

  /*
   * this routine takes the dataset of an article and will find
   * all relevant attachement files/folders
   * all links will be checked to be valid
   * 
   * output is an array with absolute file links
   * 
   */
  function filterValidMedia( $article ){
  
    global $mediaFields;
    global $mediaIgnore;
    global $failedMediaLinks;
    
    $media = array();
    
    // go through all available fields
    foreach ($mediaFields as $field ){
      
      // and check if it is correctly set
      if (!isset( $article[$field] )){
        // try next field
        continue;
      }
      
      // get the value
      $filename = mb_strtolower( $article[$field], "UTF-8" );
      
      // check the value against our ignore list
      $ignore=0;
      foreach ($mediaIgnore as $ignoreName ){
        if (strcasecmp( $filename, $ignoreName ) == 0){
          $ignore=1;
          break;
        }
      }
      if ($ignore==1){
        // try next field
        continue;
      }
      
      // check if this is an URL
      if (preg_match("/(https?|ftp)\:\/\//", $filename)){
        $url= $filename;
        if (!url_exists($url)){
          error("filterValidMedia();", "url does not exists ".$url );
          continue;
        } else {
          error("filterValidMedia();", "url check OK ".$url );
          $media[] = $filename;
          continue;
        }
      }
      
      // replace mapped drive by unc
      $filename=str_ireplace("w:\\", "\\\\192.168.0.241\\Daten\\", $filename);
      $filename=str_ireplace("o:\\", "\\\\192.168.0.6\\Daten\\", $filename);
      $filename=str_ireplace("t:\\", "\\\\192.168.0.252\\HSEB-temp\\", $filename);
      
      // double check if the file or folder really exists
      // php file access is always ISO-8859-1 
      if (!file_exists(utf8_decode($filename))){
        // skip and try next field
        error("filterValidMedia();", "attachment does not exist ".$filename );
        $failedMediaLinks++;
        continue;
      }
      
      // if we found a directory then check all files within directory
      // php file access is always ISO-8859-1 
      if (is_dir(utf8_decode($filename))){
	dir_contents_recursive( $filename, $result, $fileCount );
        // add all files from subdir
	foreach ($result as $newitem){
	  $media[] = $newitem;
        }
        // no need to add the directory - thus continue with next field
        continue;
      }
      
      // finally add valid file link to the media array
      $media[] = $filename;

    }
    
    
    // remove duplicate items
    $media_unique = array_unique ( $media );
    
    return $media_unique;
  }
  
  /*
   * search recursivly in a folder and apply a filemask
   * 
   * output will be a list of files
   * 
   */
  function dir_contents_recursive($dir, &$result=array(), &$fileCount ) {
	  
	  //file_put_contents( "dir.log", $dir."\n", FILE_APPEND );
	  if (!file_exists( $dir )){
		error( "dir_contents_recursive()", "directory does not exist ".$dir );
		return $result;
	  }
	  
	  $fileCount++;
	  if ($fileCount > 50 ){
                error( "dir_contents_recursive();", "too many files in the folder ".$dir );
		return $result;
	  }
	  
	  
      // open handler for the directory
      $iter = new DirectoryIterator(  utf8_decode( $dir ) ); // php file access is always ISO-8859-1 

      foreach( $iter as $item ) {
	  // make sure we don't try to access the '.' or '..'
          if ($item->isDot()){
            continue;
          }
          
          // if we have a directory go for subdirs
          if( $item->isDir() ) {
            // call the function on the folder
            dir_contents_recursive( utf8_encode( $dir."/".$item ), $result, $fileCount);
            continue;
          }
          
          // add files
          $file =  $dir . "/" .utf8_encode( $item->getFilename() );
          $result[] = $file;

      }
      
      return $result;
  }

  /*
   * this function filters the list of media especially for images
   * 
   * output is a list of images
   * 
   */
  function filterImageMedia( $media ){
    global $mediaThumbnail;
    
    $imageMedia = array();
    
    foreach ($media as $fileitem ){
      
      if (is_file( utf8_decode( $fileitem ))){ // php file access is always ISO-8859-1 
	$info = pathinfo( $fileitem );
	
	if (isset($info["extension"])){
	  $ext = $info["extension"];
	  
	  if (in_arrayi( $ext, $mediaThumbnail)){
	    $imageMedia[] = $fileitem;
	  }
	}
      }
    }
    
    // unique
    $imageMedia = array_unique( $imageMedia );
    
    return $imageMedia;    
  }
   
  
  
  function cacheThumbnail( $imageMedia, $targetFile, $width=800, $height=600 ){
    
    global $articlesWithoutThumbnails;
    global $cachedMediaToday;
    
    $count=sizeof($imageMedia);
    if ($count <= 0){
      error("cacheThumbnail(); no valid images found ".$targetFile );
      $articlesWithoutThumbnails++;
      return;
    }
    
    // search preferably for an image
    $imageFile= $imageMedia[0];
    foreach ($imageMedia as $fileitem ){
      $info = pathinfo( $fileitem );
      $ext = $info["extension"];
      if (strcasecmp( $ext, 'pdf')==0){
        $imageFile= $fileitem;
        break;
      }
    }
    
    
    // check if the thumbnail already exists - otherwise we skip this
    if ((!file_exists($targetFile)) ||(date("w") == FULL_THUMBNAIL_DAY)){  // tag der woche ist samstag

      $cachedMediaToday++;
      
      $img = new imagick(); // [0] can be used to set page number
      $img->setResolution(90,90);

      // load image
      $info = pathinfo( $imageFile );
      $ext = $info["extension"];

      try {
        
        if (strcasecmp( $ext, "pdf")==0){
          // load PDF
          $img->readImage($imageFile.'[0]');
        } else {
          // load other image
          $img->readImage($imageFile );
        }
        
      } catch (Exception $ex) {
        error("cacheThumbnail();", "failed to load from file ".$imageFile );
        return;
      }
    
      // setup image parameters
      $img->setImageFormat( "jpeg" );
      $img->setImageCompression(imagick::COMPRESSION_JPEG); 
      $img->setImageCompressionQuality(90); 
      $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

      // scale down to final size
      $img = $img->flattenImages();
      $img->resizeimage($width, $height, Imagick::FILTER_LANCZOS, 0.9, true);

      // write file to disk
      $img->writeimage( $targetFile );
    } else {
      //lg( "thumbnail already exists");
    }
    
  }

  
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
  
  global $failedMediaLinks;
  global $cachedMediaToday;
  global $articlesWithoutThumbnails;
  
  // get all articles
  $sql = "SELECT * FROM `".DB_ARTICLE."` WHERE 1 ";
  $result = dbExecute($sql);  

  // go through each of them
  $updateArray= array();
  $count= 0;
  $totalCount= $result->rowCount();
  foreach ($result as $article){

    backTrace( "dbCreateArticleThumbnails ". $article["nummer"]);
    $count++;

    // need the article ID
    $articleID= $article["article_id"];

    // set thumbnail directory
    $filename= "thumb-".$articleID.".jpg";
    
    // find suitable media files within the article
    $media= filterValidMedia( $article );
    // if no attachment was found
    if (empty($media)){
      continue;
    }
    
    // filter the media to images only
    $imageMedia = filterImageMedia( $media );
    // if no images are in - skip this article
    if (empty($imageMedia)){
      continue;
    }
    
    cacheThumbnail( $imageMedia, CACHE_FOLDER.$filename, 320/2, 240/2 );    

	lg( $count ."/". $totalCount. " ".ceil($count/$totalCount*100)."%" );

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
  
  if ($failedMediaLinks){
    report("found ".$failedMediaLinks." incorrect file links in articles"); 
  }
	
  if ($cachedMediaToday > 0){
	report("today I was able to update ".$cachedMediaToday." new thumbnails");
  }
  if ($articlesWithoutThumbnails > 0){	
    report("btw. there are still ".$articlesWithoutThumbnails." articles without attachement :( ");
  }
}


