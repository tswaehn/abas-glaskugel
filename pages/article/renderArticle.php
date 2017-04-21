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
	
    $text = '<span id="abas_nr"><a href="'.$link.'">'.$article["nummer"].'</a></span>';
    $text .= ' <span id="caption_L">'.renderCaption($article).'</span>';
    $text .= ' <span id="desc">';
	$text .=  implode( $strings, " ");
	$text .= ' '.renderKennzeichen( $article["kenn"] );
	$text .= ' rank:'.$article["rank"];
	
    $text .= ' <br>'.renderBestand( $article );
    $text .= '</span>';
    $text .= '<br>';
    
    return $text ;
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
    
    $link = "?action=article&article_id=".$article["article_id"];
    
    $text= '<a href="'.$link.'"><img border="0" src="'.$thumbnail.'"'.$size.'/></a>';
    
    return $text;
  }
  
?>
