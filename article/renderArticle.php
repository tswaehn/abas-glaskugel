<?php

  function stringToArray( $array ){
	
	foreach( $array as $value ){
	
	
	}
  
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
    
	$strings = array( $name, $article["ebez"], $article["bsart"], $article["ynlief"], $article["zeichn"] );
	
    $text = '<span id="abas_nr"><a href="'.$link.'">'.$article["nummer"].'</a></span>';
    $text .= ' <span id="such">'.$article["such"].'</span>';
    $text .= ' <span id="desc">';
	$text .=  implode( $strings, " ");
	$text .= ' '.renderKennzeichen( $article["kenn"] );
	$text .= ' rank:'.$article["rank"];
	
    $text .= ' <br>'.renderBestand( $article );
    $text .= '</span>';
    $text .= '<br>';
    
    return $text ;
  }
  
  function showThumbnail( $article ){
    
    // check if thumbnail exists
    if (!isset($article["thumbnail"])){
      $thumbnail= "";
    } else {
      // get the value
      $thumbnail= $article["thumbnail"];
    }
    
    // if empty
    if (empty($thumbnail)){
      $thumbnail= "./article/image_placeholder.png";      
    } else {
      // get thumbnail from DB
      $cacheDir= "./article/cache/";
      $thumbnail= $cacheDir. $article["thumbnail"];
    }
    
    $link = "?action=article&article_id=".$article["article_id"];
    
    $text= '<a href="'.$link.'"><img src="'.$thumbnail.'"/></a>';
    
    return $text;
  }
  
?>
