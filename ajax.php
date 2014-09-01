<?php

  include('./lib/main.php');

  $action = getUrlParam( "action" );
  
  connectToDb();
  
  switch ( $action ){
    
    case "verwendung":
	  ajaxRenderVerwendungen();
	  break;
	  
    case "fertigungsliste":
	  ajaxRenderFertingsliste();
	  break;
	  
    default:
	  
	  echo "ajax ";  
  }
  
  


?>
