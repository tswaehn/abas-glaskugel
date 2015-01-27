<?php

   


  function renderInfo( $article ){
    div("artikel","articleview");
    disp( shortArticle( $article ));
    //disp( '<span id="abas_nr">'.$article["nummer"].'</span>'.'<span id="such">'.$article["such"].'</span>' );
    //disp( $article["sucherw"] );
    disp();
    
    disp( "Erstellt ".$article["erfass"]." von ".$article["yersteller"] );
    disp( "Änderung ".$article["stand"]." von ".$article["zeichen"] );
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
  
  echo '<div id="searchform">';
    
    $abas_nr = getUrlParam("search_abas_nr");
    
    if (!empty($abas_nr)){
    
      $result = getArticleByAbasNr( $abas_nr );
      $article = $result->fetch();
    
    } else {
    
      $article_id=getUrlParam( "article_id");

      if (!empty($article_id)){
      
	$result = getArticle( $article_id );
	$article = $result->fetch();
      
      }
      
    }
      
      
    echo '<form action="?action=article" method="POST">
	    ABAS Nr.: <input type="edit" name="search_abas_nr" value="'.$abas_nr.'">
	    <input type="submit" value="öffnen">
	  </form>';    
      
    
  echo '</div>';

  
  echo '<div id="articleFrame">';

  if (empty($article)){
    echo 'Artikel nicht gefunden. Bitte korrekte Artikelnummer eingeben, oder vielleicht einen Artikel <a href="?action=search">suchen</a>?';
    die();
  }
   

  echo "<table>";
    echo "<tr><td>";
      renderMedia( $article );
      
      renderEinkaufBestellung( $article );
    echo "</td><td>";
      renderInfo( $article );
      renderLager($article );    
    
      renderSimilar( $article );
    echo "</td></tr>";

  echo "</table>";
  
  renderVerwendungen($article );

  //renderFertigung($article);
  
  renderFertingsliste( $article );  
  
  echo '</div>';
  
?>
