<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  echo '<div id="searchform">';

    // --- search block
    echo '<div id="searchformfield">';
    $search = getUrlParam('search');
    $searchFilters= getUrlParam('searchFilters');
    
    echo '<form id="search_form" action="?action=search" method="POST">
          <span style="margin-right:10px">Suchbegriff </span>
	  <input type="text" name="search" value="'.$search.'" size="40">
	  <input type="submit" value="suchen"> ';
    echo '<p>';
    
    
    
    // --- 
    echo '<div id="div_search_filters">';
    if (is_array($searchFilters)){
      // check if there is something to do for filters
      $temp= $searchFilters;
      $searchFilters= array();
      // go through all items
      foreach ($temp as $item){
        // filters come like "filter_Ersatzteil_ja" so need to cut them into pieces
        $itemArr= explode("_", $item);
        
        // add filter
        $searchFilters[$itemArr[1]][]= $itemArr[2];
        
        // create display text
        $dispText= $itemArr[1].":".$itemArr[2];
        
        // render the filter text
        echo ' <span id="'.$item.'" class="remove_filter">['.$dispText.'<a href="#"><img src="./search/cross.png"></a>]</span> ';
        echo '<input id="'.$item.'" type="hidden" name="searchFilters[]" value="'.$item.'"> ';
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
	    ABAS Nr.: <input type="text" name="search_abas_nr" value="'.$abas_nr.'">
	    <input type="submit" value="öffnen" class="ui-button ui-widget ui-corner-all" >
	  </form>';    
    echo "</div>";
    
    echo '<div id="clearfloat"></div>';
  echo '</div>';


?>