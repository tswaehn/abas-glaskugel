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
    echo '<form action="?action=search" method="POST">
		    <span style="margin-right:10px">Suchbegriff </span>
	  <input type="edit" name="search" value="'.$search.'" size="40">
	  <input type="submit" value="suchen">
	  </form>';    
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