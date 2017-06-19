<?php

   


  function renderInfo( $article ){
    global $botList;
  	  	
  	div("artikel","articleview");
    disp( shortArticle( $article ));
    //disp( '<span id="abas_nr">'.$article["nummer"].'</span>'.'<span id="such">'.$article["such"].'</span>' );
    //disp( $article["sucherw"] );
    disp();
    
    disp( "Erstellt ".$article["erfass"]." von ".$article["yersteller"] );
    $editData = getLastEditor($article);
    if (in_array($article['zeichen'], $botList) && $editData["editor"] != $article["zeichen"]) {
    	disp( "Änderung ".$editData["edittime"]." von ".$editData["editor"].' <span style="color:grey;">('.$article["zeichen"].' am '.$article["stand"].')</span>');
    } else {
    	disp( "Änderung ".$article["stand"]." von ".$article["zeichen"] );
    }
    //disp( "Version ".$na );
    ediv();
  }
  
  


  function renderSimilar( $article ){
    div("","articleview");  
    disp('<span id="caption">Ähnliche Artikel</span><br>');
          
    $result = getSimilarItems( $article );
    
    foreach ($result as $item ){
    
      disp( shortArticle( $item ) );
    }
    
    ediv();
  }

  function renderOrders( $article ){
    div("artikel");  
    disp('<span id="caption">Orders</span><br>');
    disp('<span id="caption">Orders</span><br>');
      
    $result = getSimilarItems( $article );
    
    foreach ($result as $item ){
    
      disp( shortArticle( $item ) );
    }
    
    ediv();
  }
    // -------------------
  
  echo '<div id="articleFrame">';

  if (empty($article)){
    echo 'Artikel nicht gefunden. Bitte korrekte Artikelnummer eingeben, oder vielleicht einen Artikel <a href="?action=search">suchen</a>?';
  } else {
   
	  echo "<table>";
	    echo "<tr><td>";
	    	$starttime = microtime(true);
	    	renderMedia( $article );
	    	$endtime = microtime(true);
	    	$timeDiffMedia = $endtime-$starttime;
	    	
	    	$starttime = microtime(true);
	      	renderEinkaufBestellung( $article );
	      	$endtime = microtime(true);
	      	$timeDiffBest = $endtime-$starttime;
	    echo "</td><td>";
	      renderInfo( $article );
	      $starttime = microtime(true);
	      renderLager($article );    
	      $endtime = microtime(true);
	      $timeDiffLager = $endtime-$starttime;
	      
	      $starttime = microtime(true);
	      renderSimilar( $article );
	      $endtime = microtime(true);
	      $timeDiffSim = $endtime-$starttime;
	    echo "</td></tr>";
	
	  echo "</table>";
	  $starttime = microtime(true);
	  renderVerwendungen($article );
	  $endtime = microtime(true);
	  $timeDiffVerw = $endtime-$starttime;
	  
	  //renderFertigung($article);
	  $starttime = microtime(true);
	  renderFertingsliste( $article );  
	  $endtime = microtime(true);
	  $timeDiffFertigung = $endtime-$starttime;
	  
	  echo '<!-- media: '.$timeDiffMedia.' Bestellung: '.$timeDiffBest.' Lager: '.$timeDiffLager.' similar: '.$timeDiffSim.' verwendung: '.$timeDiffVerw.' fertigung: '.$timeDiffFertigung.' -->';
	  echo '<!-- all: '.($timeDiffMedia+$timeDiffBest+$timeDiffLager+$timeDiffSim+$timeDiffVerw+$timeDiffFertigung).' -->';
  }
  echo '</div>';
?>
