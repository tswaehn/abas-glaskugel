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

  
?>
