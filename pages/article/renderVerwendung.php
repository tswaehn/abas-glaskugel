<?php
  

  function findRecursiveParents( $allLinks, &$subTree ){
    
    if (empty($subTree)){
      return;
    }
    
    foreach( $subTree as $article=>$array ){
      if (isset($allLinks[$article])){
        $subArticles= $allLinks[$article];
        if (is_array($subArticles)){
          foreach ($subArticles as $subArticle){
            $subTree[$article][$subArticle]= array();
            findRecursiveParents( $allLinks, $subTree[$article]);        
          }

        }
      }
    }
    
  }
  
  function renderRecursive( $articles, $tree ){

      // 
    echo '<ul class="list">';
    foreach ($tree as $article_id=> $array){
      if (!empty($array)){
        $click= '<span class="Collapsable" ><a>+</a>';
      } else {
        $click= '<span class="NotCollapsable" >';
      }
      $link= '<a href="?action=article&article_id='.$article_id.'">'.$articles[$article_id]["nummer"].'</a>';
      
      echo '<li>'.$click.' '.$link.' '.$articles[$article_id]["such"].'</span>';
      renderRecursive($articles, $array);
      echo '</li>';
    }
    echo "</ul>";
  }
  
  function renderVerwendungen( $article ){

    div("", "articleview");
    disp('<span id="caption">Verwendung</span><br>');
    
      // get all production list entries
      $sql= "SELECT `list_nr`, `article_id`, `elem_id` FROM ".q(DB_PRODUCTION_LIST)." WHERE 1 ORDER BY `article_id` ASC";
      $result= dbExecute($sql);
      // load them into an array
      $allLinks=array();
      foreach ($result as $item){
        $parent= $item["article_id"];
        $child= $item["elem_id"];
        $allLinks[$child][]= $parent;
      }
      
      // now generate the tree
      $tree= array( $article["article_id"]=> array() );
      findRecursiveParents($allLinks, $tree);

      // get all article infos
      $sql= "SELECT `article_id`,`nummer`,`such` FROM `gk_article` WHERE 1";
      $result= dbExecute($sql);
      $articles= array();
      foreach ($result as $item){
        $article_id= $item["article_id"];
        $such= $item["such"];
        $nummer= $item["nummer"];
        $articles[$article_id]= array("nummer"=>$nummer, "such"=>$such);
      }
      

      renderRecursive( $articles, $tree );                  

            echo "<script>
                $('.Collapsable').click(function () {

                    $(this).parent().children().toggle();
                    $(this).toggle();

                });

                $('.Collapsable').each(function(){

                        $(this).parent().children().toggle();
                        $(this).toggle();
                });
        </script>";
            
    
    ediv();
  }

  function renderVerwendungenX( $article ){
    div("", "articleview");
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
