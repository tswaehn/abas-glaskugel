<?php

  
  function checkForSiteDown(){
    
    if( getConfigDb("dbLink") != 1 ){
    
	echo '<div id="down">';
	echo "<h1>Service ist momentan nicht verfügbar</h1>";
	//echo date("r");
	echo getConfigDb("lastSync");
	echo '</div>';
	die();
    }
    
  }


?>
