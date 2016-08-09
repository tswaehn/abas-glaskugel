<?php
  
  function createLike( $cols, $value ){
    
    $likeStatement='';

    $likeStatement .='(';
    $first = $cols[0];
    foreach ($cols as $col){
      if ($col == $first){
	$likeStatement .= $col. " LIKE '%".$value."%'";
      } else {
	$likeStatement .= ' OR '.q($col). " LIKE '%".$value."%'";
      }
    } 
    
    $likeStatement .=')';
    
    return $likeStatement;
  }
  
    
  function mySearchInArticles( $search, $validArticles ){
    global $pdo;
    
    $searches = preg_split( "/( )/", $search, -1, PREG_SPLIT_NO_EMPTY );
    //print_r($searches);
    
    $columns = getColumns( DB_ARTICLE );
    
    // define all columns which need to be searched through
    $whereArr= array();
    foreach ($searches as $search ){
      $whereArr[]= createLike( $columns, $search);
    }
    $where= implode(" AND ", $whereArr);
    
    // define search restriction
    if (is_array( $validArticles )){
      // restrict search on defined articles
      $valid_article_ids= implode(",", $validArticles);
      $ext= ' AND (`article_id` IN ('.$valid_article_ids.') )';
    } else {
      // no restriction
      $ext= "";
    }
            
    // finally the query
    $sql = 'SELECT `article_id` FROM '.q(DB_ARTICLE).' WHERE ('. $where .') '.$ext.' ORDER BY rank ASC;';
    
    try {
	lg($sql);
	$starttime = microtime(true); 
	$result = $pdo->query( $sql);
	$endtime = microtime(true); 
	$timediff = $endtime-$starttime;
    } catch (Exception $e) {
	lg("search failed");
	return;
    } 
    
    lg('exec time is '.($timediff) );

    if (!empty($result)){
      lg('found '.$result->rowCount().' items' );
    }
    
    return $result;

  }


  function mySearch( $search, $validArticles ){
      
      global $searchResultText;
      $count = 0;
      
      $start=microtime(true);
      $result = mySearchInArticles( $search, $validArticles );
      $end=microtime(true);
      
      $diff = number_format( $end-$start, 3) ;      
      
      if (!empty($result)){
	$count=$result->rowCount();
      
        $searchResultText= '<span class="search_report">Habe '.$count.' Ergebnisse in '.$diff.' Sekunden gefunden. </span>';
      }
      
      addClientInfo( $search );
      addClientInfo("res ".$count." ".$diff );
      
      $result_arr= array();
      if (!empty($result)){
	$count=$result->rowCount();
        foreach ($result as $item){
          $result_arr[]= $item["article_id"];
        }
      }
      
      return $result_arr;
  }
  
  function renderSearchResult( $foundArticleIDs ){

      // define search restriction
      if (!is_array( $foundArticleIDs )){
        return;
      }
      
      // prepare the article_id
      $article_ids= implode(",", $foundArticleIDs);

      // query for the full articles
      $sql = 'SELECT * FROM '.q(DB_ARTICLE).' WHERE (`article_id` IN ('.$article_ids.')) ORDER BY rank ASC;';
      $result= dbExecute($sql);
      
      
      if (!empty($result)){

	foreach ($result as $item){
	
	  echo '<div id="search_item">';
	  
		echo '<div style="height:60px; width:90px; float:left; margin-right:10px; text-align:center; ">';
            echo showThumbnail( $item );
		echo '</div>';
		
	    echo shortArticle( $item );
	  echo '</div>';
	}
      } else {
	
	//echo "empty result";
      }

  }
  
  function renderFullTags( $result ){

      if (empty($result)){
        return;
      }
      
      echo '<script>

          $(document).ready(function() {
            console.log("start");

            $("#div_search_filters").on("click", ".remove_filter", function(event){
              var item= $(this);
              var id= $(this).attr("id");
              
              console.log("removing "+ name );
              
              var ip= $( "[id="+id+"]");
              ip.remove();
              item.remove();
              
              document.getElementById("search_form").submit();

            });

            $(".filtersX").on("click", function(event){
              var name= $(this).attr("id");
              console.log("testing"+ name);
              
              $("#filters").append( name );
              
              $("<input>").attr({
                  type: "hidden",
                  name: "searchFilters[]",
                  value: name
              }).appendTo("form");

              document.getElementById("search_form").submit();
            });

          });
          
          
        </script>';
      
  
      // prepare all groups with tag names
      $groups= array();
      foreach ($result as $item){
        $group_name= $item["group_name"];
        $tag_name= $item["tag_name"];
        $groups[$group_name][]= $tag_name;
      }

      // load all existing/already enabled filters
      global $searchFilters;
      
      // print groups and tags
      $keys= array_keys( $groups );
      foreach ($keys as $group_name){
        echo "<br>".$group_name."<br>";
          echo '<ul class="navi-li">';
          foreach( $groups[$group_name] as $tag_name){
            if (isset($searchFilters[$group_name]) && (in_array( $tag_name, $searchFilters[$group_name]))){
              echo '<li ><span class="ui-button ui-state-disabled">'.$tag_name.'</span></li>';
            } else {
              echo '<li><a id="filter_'.$group_name.'_'.$tag_name.'" class="filtersX ui-button" href="#">'.$tag_name.'</a></li>';
            }
          }
          echo "</ul>";
        echo "</li>";
      }
        
  }
  
  function renderSearchTags( $article_IDs ){
  
      if (empty($article_IDs)){
        return;
      }
      
      // lookup all suiting tags of articles
      $search= implode(",", $article_IDs);
      $sql= "SELECT * FROM `gk_article_tags` WHERE `article_id` IN (".$search.") GROUP BY `tag`";
      $result = dbExecute( $sql );
      $tags= array();
      foreach( $result as $item){
        $tags[]= $item["tag"];
      }
      
      // lookup suiting full tag info for all tags
      $search= implode(",", $tags);
      $sql= "SELECT * FROM `gk_article_groups` WHERE `tag` IN (".$search.")";
      $result = dbExecute( $sql );
      
      renderFullTags( $result );
    
  }
  
  // ---
  
  $search = getUrlParam('search');
  if ($search ==''){
    $search ='';
  }
  
  $search = preg_replace( ALLOWED_ASCII, " ", $search );
  $search = trim( $search );
  
  
  // apply all filter tags
  $searchFilters;
  $tagIDs= getAllTagIDs( $searchFilters );
  $validArticles= getAllTagFilteredArticles( $tagIDs );  
  
  // actually search for the article_ids
  $foundArticleIDs= mySearch($search, $validArticles);
  
  
  echo '<table>';
  echo '<tr><td>';
      echo '<div id="search_navi">';
      renderSearchTags($foundArticleIDs);
      echo '</div>';

  echo '</td><td>';
      echo '<div id="searchresult">';
      echo $searchResultText;
      renderSearchResult($foundArticleIDs);
      echo '</div>';
      
  echo '</td></tr>';
  echo '</table>';
  

?>
