<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  echo '<script>



      </script>';
  echo '<div id="searchform">';

    // --- search block
    echo '<div id="searchformfield">';
    $search = getUrlParam('search');
    $sparePart= getUrlParam('searchSparePart');
    $salesPart= getUrlParam('searchSalesPart');
    
    $filters= getUrlParam('filters');
    
    echo '<form id="search_form" action="?action=search" method="POST">
          <span style="margin-right:10px">Suchbegriff </span>
	  <input type="edit" name="search" value="'.$search.'" size="40">
	  <input type="submit" value="suchen"> ';
    echo '<p>';
    
    
    /*
          <br><br>
          <input type="checkbox" name="searchSparePart" value="1" '. ($sparePart==1?"checked":"") .'> Ersatzteil 
          <input type="checkbox" name="searchSalesPart" value="1" '. ($salesPart==1?"checked":"") .'> Vertriebsartikel
      */      
    
    // --- 
    echo '<div id="filters">';
    if (is_array($filters)){
      foreach ($filters as $item){
        $itemArr= explode("_", $item);
        $dispText= $itemArr[1].":".$itemArr[2];
        echo ' <span id="'.$item.'" class="remove_filter">['.$dispText.'<a href="#"><img src="./search/cross.png"></a>]</span> ';
        echo '<input id="'.$item.'" type="text" name="filters[]" value="'.$item.'"> ';
      }
    }
    echo '</div>';

  
    echo '</form>';    
    echo "</div>";



    // --- direct article block
    echo '<div id="searchformfield">';
    
    $abas_nr = getUrlParam("search_abas_nr");
    if (!empty($abas_nr)){

       $result = getArticleByAbasNr( $abas_nr );
       $article = $result->fetch();

    } else {

       $article_id=getUrlParam( "article_id");

       if (!empty($article_id)){

         $result = getArticle( $article_id );
         $article = $result->fetch();
         $abas_nr= $article["nummer"];
       }

    }
    
    echo '<form action="?action=article" method="POST">
	    ABAS Nr.: <input type="edit" name="search_abas_nr" value="'.$abas_nr.'">
	    <input type="submit" value="öffnen">
	  </form>';    
    echo "</div>";
    
  echo '</div>';


?>