<?php

  function stringToArray( $array ){
	
	foreach( $array as $value ){
	
	
	}
  
  }
  
  function removeLineFeed( $text ){
    $text= preg_replace("/\[-10-\]/", " ", $text );
    return $text;
  }
  
  function renderCaption( $article ){
    $such= $article["such"];
    $salesNameDE= $article["vkbez"];
    $salesLongNameDE= $article["vbezbspr"];
    $salesNameEN= $article["vkbez2"];
    $salesLongNameEN= $article["vbez2"];
    
    $buyInName= $article["ebez"];
    
    if (!empty($salesNameEN)){
      return removeLineFeed($salesNameEN);
    }
    if (!empty($salesLongNameEN)){
      return removeLineFeed($salesLongNameEN);
    }
    
    if (!empty($salesNameDE)){
      return removeLineFeed($salesNameDE);
    }
    if ((!empty($salesLongNameDE)) && ($buyInName != $salesLongNameDE)){
      return removeLineFeed($salesLongNameDE);
    }
    
    return $such;
  }
  
  
  function renderKennzeichen( $kenn ){
    $str = "";
    
    if (empty($kenn)){
      return $str;
    }
    
    if (strpos($kenn, "X")!==false){
      $str .= "wird gelöscht ";
    }

    if (strpos($kenn, "S")!==false){
      $str .= "gesperrt ";
    }

    if (strpos($kenn, "N")!==false){
      $str .= "hat nachfolger ";
    }

    if (strpos($kenn, "L")!==false){
      $str .= "Langläufer ";
    }
    
    $str .= "(".$kenn.")";
    
    
    return $str;
  }
  
  function shortArticle( $article ){
    $link = "?action=article&article_id=".$article["article_id"];
    
    // remove everything after "@@"
    $name= explode( "@@", $article["name"]);
    $name= $name[0];
    
    $strings = array( '['.mb_strtolower($article["such"], 'UTF-8').']', $name, $article["ebez"], $article["bsart"], $article["ynlief"], $article["zeichn"] );
    $desc = implode( $strings, " ");
    $desc = linkArticleNumbers($desc);
    
    $text = '<span id="abas_nr"><a href="'.$link.'">'.$article["nummer"].'</a></span>';
    $text .= ' <span id="caption_L">'.renderCaption($article).'</span>';
    $text .= ' <span id="desc">';
	$text .=  $desc;
	$text .= ' '.renderKennzeichen( $article["kenn"] );
	$text .= ' rank:'.$article["rank"];
	
    $text .= ' <br>'.renderBestand( $article );
    $text .= '</span>';
    $text .= '<br>';
    
    return $text ;
  }
  
  
  function filterImageMedia($media) {
	
  	$mediaThumbnail = array( "png", "jpg", "jpeg", "gif", "tif", "pdf");
  	$imageMedia = array();
  	
  	foreach ($media as $fileitem) {
  		if (is_file( utf8_decode( $fileitem ))){ // php file access is always ISO-8859-1
  			$info = pathinfo( $fileitem );
  			
  			if (isset($info["extension"])) {
  				$ext = $info["extension"];
  				if (in_arrayi($ext, $mediaThumbnail)) {
  					$imageMedia[] = $fileitem;
  				}
  			}
  		}
  	}
  	
  	$imageMedia = array_unique($imageMedia);
  	return $imageMedia;
  }
  
  
  /**
   *  Render thumbnail image with link
   * 
   * @param array $article 
   * @param number $width (optional) width of image
   * @return string html code clickable image
   */
  function showThumbnail( $article, $width = 0 ){
    
  	//adjust size if given
  	if (is_int($width) && $width > 0) {
  		$size = ' width="'.$width.'"';
  	} else {
  		//no size given, use original image dimensions
  		$size = '';
  	}
  	
  	
    // check if thumbnail exists
    if (!isset($article["thumbnail"])){
      $thumbnail= "";
    } else {
      // get the value
      $thumbnail= $article["thumbnail"];
    }
    
    // if empty
    if (empty($thumbnail)){
      $thumbnail= "./pages/article/image_placeholder.png";  
    } else {
      // get thumbnail from DB
      $cacheDir= "./pages/article/cache/";
      $thumbnail= $cacheDir. $article["thumbnail"];
    }
    
    if (!is_readable($thumbnail)) {
    	//generate new thumbnail
    	$imageMedia = filterImageMedia(filterValidMedia($article)); 	
    	//$imageFile = array_pop($imageMedia);
    	$imageFile = $imageMedia[0];
    	
    	$img = new imagick(); // [0] can be used to set page number
    	$img->setResolution(90,90);
    	
    	// load image
    	$info = pathinfo($imageFile);
    	$ext = $info["extension"];
    	
    	try {
    		if (strcasecmp( $ext, "pdf")==0){
    			// load PDF
    			$img->readImage($imageFile.'[0]');
    		} else {
    			// load other image
    			$img->readImage($imageFile);
    		}
    	} catch (Exception $ex) {
    		error("cacheThumbnail();", "failed to load from file ".$imageFile);
    		return;
    	}
    	
    	// setup image parameters
    	$img->setImageFormat( "jpeg" );
    	$img->setImageCompression(imagick::COMPRESSION_JPEG);
    	$img->setImageCompressionQuality(90);
    	$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
    	
    	// scale down to final size
    	$img = $img->flattenImages();
    	$img->resizeimage(160, 120, Imagick::FILTER_LANCZOS, 0.9, true);
    	
    	// write file to disk
    	$thumbnail = realpath($cacheDir).DIRECTORY_SEPARATOR.$article["thumbnail"];
    	$img->writeimage($thumbnail);
    }
    
    $link = "?action=article&article_id=".$article["article_id"];
    
    $text= '<a href="'.$link.'"><img border="0" src="'.$thumbnail.'"'.$size.'/></a>';
    
    return $text;
  }
  
?>
