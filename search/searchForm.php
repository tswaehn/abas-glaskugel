<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  echo '<div id="searchform">';

    // --- search block
    echo '<div id="searchformfield">';
    $form_search_text = getUrlParam('form_search_text');
    $searchFilters= getUrlParam('searchFilters');
    
    if ($form_search_text == ""){
      $search= getSessionVar("search");
    } else {
      $search= $form_search_text;
    }
    setSessionVar( "search", $search);
    
    echo '<form id="search_form" action="?action=search" method="POST">
          <span style="margin-right:10px">Suchbegriff </span>
	  <input type="text" name="form_search_text" value="'.$search.'" size="40">
	  <input type="submit" value="suchen"> ';
    
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