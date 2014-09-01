<pre>

<?php
  

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
      
  echo '<form action="index.php?action=raw" method="POST">
ABAS Nr.: <input type="edit" name="search_abas_nr" value="'.$abas_nr.'">
<input type="submit" value="search">
</form>';

  $result = getArticle( $abas_nr );


    print_r($article );
    

?>


</pre>
