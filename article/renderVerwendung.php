<?php


  function renderVerwendungen( $article ){
    div("artikel");
    disp('<span id="caption">Verwendung</span><br>');
    
    /*
    echo '<div id="verwendung-ajax"></div>';    
    $show_all=getUrlParam("show_all");
    
    $updateUrl = "ajax.php?action=verwendung&abas_nr=".$article["nummer"]."&show_all=".$show_all;
    $tag="verwendung-ajax";
    insertUpdateScript( $updateUrl, $tag, $cyclic = 0 );
    */
/*    
      $starttime = microtime(true); 
    ajaxRenderVerwendungenByAbas($article["nummer"]);    
      $endtime = microtime(true); 
      $timediff = $endtime-$starttime;
      lg('exec time is '.($timediff) );    
*/
    echo "<p>";
    
    echo "<pre>";
      $starttime = microtime(true); 
    ajaxRenderVerwendungen($article["article_id"]);    
      $endtime = microtime(true); 
      $timediff = $endtime-$starttime;
      lg('exec time is '.($timediff) );
    echo "</pre>";
    
    ediv();
  }
  
  
  function ajaxRenderVerwendungenByAbas( $article_id = ""){
  
    if (empty($article_id)){
      $article_id=getUrlParam("article_id");
    }
    
    $show_all= getUrlParam("show_all");
    
    $branches = getAllParentsByAbas( $article_id, $show_all );
    
    if (in_array( "limit reached", $branches)){
      disp( 'There are more results <a href="?action=article&article_id='.$article_id.'&show_all=1">show all</a>' );
    }
    
    foreach ($branches as $branch){
      if ($branch == "limit reached"){
	continue;
      }
      
      $str = "";
      foreach ($branch as $leaf){
	$parent_res= getArticleByAbasNr( $leaf["artikel"] );
	$parent_info = $parent_res->fetch();
	
	//$str = '<ul><li>'.$leaf["artikel"].$str.'</li></ul>';
	$str = '<div id="x"><a href="?action=article&abas_nr='.$leaf["artikel"].'">'.$leaf["artikel"].'</a> '.$parent_info["such"].$str.'</div>';
      }
      disp($str);
    }    
    
  }

  function ajaxRenderVerwendungen( $article_id = ""){
  
    if (empty($article_id)){
      $article_id=getUrlParam("article_id");
    }
    
    $show_all= getUrlParam("show_all");
    
    $branches = getAllParents( $article_id, $show_all );
    
    if (in_array( "limit reached", $branches)){
      disp( 'There are more results <a href="?action=article&article_id='.$article_id.'&show_all=1">show all</a>' );
    }
    
    foreach ($branches as $branch){
      if ($branch == "limit reached"){
	continue;
      }
      
      $str = "";
      foreach ($branch as $leaf){
	$parent_res= getArticle( $leaf["article"] );
	$parent_info = $parent_res->fetch();
	
	//$str = '<ul><li>'.$leaf["artikel"].$str.'</li></ul>';
	$str = '<div id="x"><a href="?action=article&article_id='.$leaf["article"].'">'.$parent_info["nummer"].'</a> '.$parent_info["such"].$str.'</div>';
      }
      disp($str);
    }    
    
  }
  
?>
