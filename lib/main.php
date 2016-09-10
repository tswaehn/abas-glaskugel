<?php
  
  $scriptStartTime = microtime(true);
  
  include( './cli/config.php');

  include('./lib/logging.php');
  include('./lib/session.php');
  include('./lib/browserCheck.php');
  include('./lib/lib.php');
  include('./cli/dbConnection.php');  
  include('./lib/siteDown.php');
      
  include('./lib/jsUpdate.php');

    include('./pages/search/dbSearch.php');
    
    include('./pages/article/dbArticle.php');
    include('./pages/article/dbFertigung.php');
    
    include('./pages/article/articleHelpers.php');
    include('./pages/article/renderMedia.php');
    include('./pages/article/renderLager.php');
    include('./pages/article/renderFertigung.php');
    include('./pages/article/renderVerwendung.php');
    include('./pages/article/renderOrders.php');
    include('./pages/article/renderArticle.php');    

    include('./pages/stats/getRemoteInfo.php');
 
  $action = getUrlParam("action");
  if (empty($action)){
    $action="search";
  }
  
  
  connectToDb();
  
  checkForSiteDown();
  
  switch ($action){	
    case "raw": 
		$title="Raw";
		$script="./pages/article/raw.php";
		break;
		
    case "article": 
		$title ="Artikel";
		$script="./pages/article/articleView.php";
		break;
		
    case "overdrive":
		$title="oVerdRive Search";
		$script="./pages/overdrive/search.php";
		break;
		
    case "stats":
		$title="Statistik";
		$script="./pages/stats/stats.php";
		break;
		
    default:
	      $title="Suchen";
	      $script="./pages/search/mySearchEngine.php";
  
  }
  
  addClientInfo( $action );

  
  function footer(){
  
    global $scriptStartTime;

    echo '<hr style="clear:both;">';
    

    
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
    
    //echo '<a href="./cli/index.php">cli</a>';
    
    echo '</div>';
    
  }
  

?>
