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
    
	$strings = array( $article["name"], $article["ebez"], $article["bsart"], $article["ynlief"], $article["zeichn"] );
	
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
  
  
?>
