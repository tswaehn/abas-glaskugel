<?php

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
	  $name=str_ireplace("O:\\", "\\\\192.168.0.6\\Daten\\", $name);
          $media[] = $name;
	}
	
      }
    }
    
    // remove duplicate items
    $media = array_unique ( $media );
    
    return $media;
  }
  
  function renderMediaFile( $file ){
    $name = basename( $file );
    //$dir = dirname( $file );
    
    disp('<a href="./article/download.php?file='.urlencode($file).'">'.$name.'</a>');
  
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

  
  function renderMediaDir( $dir ){
    $max_display = 30;
    
    $result = dir_contents_recursive( $dir );
    
    $i=0;
    foreach ( $result as $file ){
      renderMediaFile(  $file  );
      
      // limit results
      $i++;
      if($i>=$max_display){
	break;
      }
    }
    
    if ($i==$max_display){
      $count = sizeof($result)-$max_display;
      disp('There are about '.$count.' more items');
    }

  }
  
  function renderThumbnail( $media ){
    global $mediaThumbnail;
    $max_width=500;
    $min_width=40;

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
    if ($count > 0){
      $image_width = $max_width / $count;
      if ($image_width < $min_width){
	$image_width=$min_width;
      }
      foreach ($images as $image){
	$file_link='./article/download.php?file='.urlencode($image);
	
	echo ('<a href="'.$file_link.'"><img src="'.$file_link.'" width="'.$image_width.'"  ></a>');
      }
      
      echo "<br>";
    } else {
	
      $found_something_to_display=0;
      // check for an pdf to display
      foreach ($media as $item){
	$info = pathinfo($item, PATHINFO_EXTENSION);
	if (in_arrayi( $info, array("pdf"))){
	  echo "converting pdf2jpg";
	  $found_something_to_display=1;
	  $file_dllink='./article/download.php?file='.urlencode($item);	  
	  $file_link='./article/pdfToJpeg.php?file='.urlencode($item);
	  echo ('<a href="'.$file_dllink.'"><img src="'.$file_link.'" width="'.$max_width.'"  ></a>');
	  break;
	}	
      }
      
      if ($found_something_to_display==0){
	$file_link="./article/image_placeholder.png";
	//$file_link="./article/download.php?file=/home/tswaehn/public_html/git_dev/abas/website/article/image_placeholder.png";
	echo ('<img src="'.$file_link.'" width="'.$max_width.'" >');
      }
    }
    echo "<br>";
  }
  
  
  
  function renderMedia( $article ){
    div("media","articleview");
  
    $media = filterValidMedia( $article );
    
    //disp( '<a href="./article/pdfToJpeg.php?file=test.pdf">download</a>' );
    
    // add test folder
    //$media[] = "/home/tswaehn/public_html/git_dev/abas/website/";
    
    // find a thumbnail for display
    renderThumbnail( $media );

    // render each item
    foreach ($media as $item){
      if (is_file( utf8_decode($item) )){	// php file access is always ISO-8859-1 
	renderMediaFile( $item );
      } else if (is_dir( utf8_decode( $item ))){	// php file access is always ISO-8859-1 
	renderMediaDir( $item );
      } else {
	disp("non-existent media ".$item );
      }
      
    }
    
    ediv();  
    
    
    
  }
  
  
?>

