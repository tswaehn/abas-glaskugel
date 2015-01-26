<?php
  
  $scriptStartTime = microtime(true);
  
  include( 'config.txt');

  include('./lib/browserCheck.php');
  include('./lib/lib.php');
  include('./cli/dbConnection.php');  
  include('./lib/siteDown.php');
      
  include('./lib/jsUpdate.php');

    include('./search/dbSearch.php');
    
    include('./article/dbArticle.php');
    include('./article/dbFertigung.php');
    
    include('./article/articleHelpers.php');
    include('./article/renderMedia.php');
    include('./article/renderLager.php');
    include('./article/renderFertigung.php');
    include('./article/renderVerwendung.php');
    include('./article/renderArticle.php');    

    include('./stats/getRemoteInfo.php');
 
  $action = getUrlParam("action");
  if (empty($action)){
    $action="search";
  }
  
  
  connectToDb();
  
  checkForSiteDown();
  
  addClientInfo( $action );
  
  switch ($action){	
    case "raw": 
		$title="Raw";
		$script="./article/raw.php";
		break;
		
    case "article": 
		$title ="Artikel";
		$script="./article/articleView.php";
		break;
		
    case "overdrive":
		$title="oVerdRive Search";
		$script="./overdrive/search.php";
		break;
		
    case "stats":
		$title="Statistik";
		$script="./stats/stats.php";
		break;
		
    default:
	      $title="Suchen";
	      $script="./search/mySearchEngine.php";
  
  }
  
  
  
  function footer(){
  
    global $scriptStartTime;

    echo "<hr>";
    

    
    $scriptStopTime=microtime(true);
    $duration = $scriptStopTime-$scriptStartTime;

    getRemoteInfos($duration);
    
    $delta = number_format( $duration, 3 );
    
    echo '<div id="footer">';
    echo "request finished in ".$delta."sec - ".'<a href="?action=stats">stats</a><br>';
    echo 'Glaskugel <a href="./lib/history.php" target="_blank">'.BUILD_NR.'</a>';
    echo " - ";
    echo "letzter sync ".getConfigdb("lastSync")."<br>";
   // echo 'Im Internet <a href="http://abas.metagons-software.de" target="_blank" >abas.metagons-software.de</a>';
    browserCheck();
    echo '</div>';
    
  }
  

?>
