<?php
  function out( $text ){
    echo $text;
  }
  
  function disp( $text = ""){
    
    echo $text."<br>";
    
  }
  
  function div( $id, $class="" ){
    echo '<div id ="'.$id.'" class="'.$class.'">';
  }

  function ediv(){
    echo "</div>";
  }

  
  /**
   * add link to every article number in text
   * 
   * @param String $text
   * @return String same text with every ABAS article number linked
   */
  function linkArticleNumbers($text) {
  	
  	$articleSearch = '/([0-9]{4})-([0-9]{5})/';
  	$replace = '<a href="?action=article&article_id=$1$2">$0</a>';
  	$str = preg_replace($articleSearch, $replace, $text);
  	return $str;
  }
  
?>
