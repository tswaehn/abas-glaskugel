<?php
  
  define("SESSION_VAR_SEARCH_TEXT", "search_text");
  define("SESSION_VAR_FOUND_ARTICLES", "found_articles");
  define("SESSION_VAR_SEARCH_FILTERS", "serach_filters");
  define("SESSION_VAR_NEED_NEW_SEARCH", "need_new_search");
  define("SESSION_VAR_VALID_ARTICLES", "valid_articles");
  define("SESSION_VAR_SEARCH_PAGE", "search_page");
  define("SESSION_VAR_SEARCH_RESULT_TEXT", "search_result_text");

  
  // config
  define("ITEM_COUNT_PER_PAGE", 100);
  define("LONG_PAGE_COUNT", 11);
  
class SearchEngine {
  
  var $searchText;
  var $foundArticles;
  var $searchFilters;
  var $needNewSearchFlag;
  var $validArticles;
  var $searchPage;
  var $searchResultText;
  
  function __construct(){
    
    $this->searchText= getSessionVar(SESSION_VAR_SEARCH_TEXT);
    $this->foundArticles= getSessionVar(SESSION_VAR_FOUND_ARTICLES);
    $this->searchFilters= getSessionVar(SESSION_VAR_SEARCH_FILTERS);
    $this->needNewSearchFlag= getSessionVar(SESSION_VAR_NEED_NEW_SEARCH);
    $this->validArticles= getSessionVar(SESSION_VAR_VALID_ARTICLES);
    $this->searchPage= getSessionVar(SESSION_VAR_SEARCH_PAGE);
    $this->searchResultText= getSessionVar(SESSION_VAR_SEARCH_RESULT_TEXT);
    
    if (!isset($this->foundArticles)){
      $this->foundArticles= array();
    }
    
    if (!isset($this->searchFilters)){
      $this->searchFilters= array();
    }
    
    if (!isset($this->needNewSearchFlag)){
      $this->needNewSearchFlag= 1;
    }
    
    if (!isset($this->validArticles)){
      $this->validArticles= array();
    }
    
    if (!isset($this->searchPage)){
      $this->searchPage= 0;
    }
  }

  function setSearchText( $text ){
    
    // replace unwanted chars
    $text = preg_replace( ALLOWED_ASCII, " ", $text );
    $this->searchText = trim( $text );
    // save search text
    setSessionVar( SESSION_VAR_SEARCH_TEXT, $this->searchText);
    
    $this->needNewSearchFlag= 1;
  }

  function addFilter( $filter, $caption ){
    
    if (empty($caption)){
      $caption= $filter;
    }
    
    // add a new filter
    $this->searchFilters[$filter]= $caption ;
    setSessionVar(SESSION_VAR_SEARCH_FILTERS, $this->searchFilters);
    
    // load all filter tags
    $this->validArticles= getAllTagFilteredArticles( $this->searchFilters );  
    setSessionVar(SESSION_VAR_VALID_ARTICLES, $this->validArticles);
    
    $this->needNewSearchFlag= 1;
  }

  function removeFilter( $filter ){
    
    // remove existing filter
    if (isset($this->searchFilters[$filter])){
      unset($this->searchFilters[$filter]);
    }
    setSessionVar(SESSION_VAR_SEARCH_FILTERS, $this->searchFilters);
    
    // load all filter tags
    $this->validArticles= getAllTagFilteredArticles( $this->searchFilters );  
    setSessionVar(SESSION_VAR_VALID_ARTICLES, $this->validArticles);
    
    $this->needNewSearchFlag= 1;
  }

  function setSearchPage( $page ){
    if ($page < 0){
      $page= 0;
    }
    if ($page > ceil((count($this->foundArticles)/ITEM_COUNT_PER_PAGE)-1)){
      $page= ceil(count($this->foundArticles)/ITEM_COUNT_PER_PAGE)-1;
    }
    $this->searchPage= $page;
    setSessionVar(SESSION_VAR_SEARCH_PAGE, $this->searchPage);
  }
  
  function search(){
    
    if ($this->needNewSearchFlag == 0){
      // do nothing
      //echo "skip searching";
      return;
    }
    //echo "really searching";

    // 
    if (empty($this->validArticles)){
      $this->validArticles= getAllTagFilteredArticles( $this->searchFilters );  
      setSessionVar(SESSION_VAR_VALID_ARTICLES, $this->validArticles);
    }
    
    // actually search for the article_ids
    $this->foundArticles= $this->mySearch($this->searchText, $this->validArticles);
    setSessionVar(SESSION_VAR_FOUND_ARTICLES, $this->foundArticles);

    // reset search flag
    $this->needNewSearchFlag= 0;
    setSessionVar(SESSION_VAR_NEED_NEW_SEARCH, $this->needNewSearchFlag);

    //
    $this->setSearchPage(0);
    
  }
  
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
    //print_r($validArticles);
    $searches = preg_split( "/( )/", $search, -1, PREG_SPLIT_NO_EMPTY );
    //print_r($searches);
    
    $columns = getColumns( DB_ARTICLE );
    
    // define all columns which need to be searched through
    $whereArr= array();
    foreach ($searches as $search ){
      $whereArr[]= $this->createLike( $columns, $search);
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
      
      $count = 0;
      
      $start=microtime(true);
      $result = $this->mySearchInArticles( $search, $validArticles );
      $end=microtime(true);
      
      $diff = number_format( $end-$start, 3) ;      
      
      if (!empty($result)){
	$count=$result->rowCount();
      
        $this->searchResultText= '<span class="search_report">Habe '.$count.' Ergebnisse in '.$diff.' Sekunden gefunden. </span>';
        setSessionVar(SESSION_VAR_SEARCH_RESULT_TEXT, $this->searchResultText);
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
  
  function renderSearchResult(  ){

      $foundArticleIDs= $this->foundArticles;
      $searchPage= $this->searchPage;
      
      // define search restriction
      if (!is_array( $foundArticleIDs )){
        return;
      }
      
      if (empty( $foundArticleIDs)){
        return;
      }
      
      // items per page
      $searchItemsPerPage= ITEM_COUNT_PER_PAGE;
      
      // create a numbered copy
      $array = array_values($foundArticleIDs);
      
      // prepare limits
      $a= ($searchPage* $searchItemsPerPage);
      if ($a < 0){
        $a= 0;
      }
      if ($a >= count($array)){
        $a= count($array)-1;
      }
      $b= $a + $searchItemsPerPage-1;
      if ($b > count($array)){
        $b= count($array)-1;
      }
      
      
      $article_ids_limited= array();
      // copy only valid articles
      for ($i=$a;$i<=$b;$i++){
        $article_ids_limited[]= $array[$i];
      }

      //echo "display items ".$a." to ".$b."<br>";
      $this->renderPageNumbers();
      
      // prepare the article_id
      $article_ids= implode(",", $article_ids_limited);

      // query for the full articles
      $sql = 'SELECT * FROM '.q(DB_ARTICLE).' WHERE (`article_id` IN ('.$article_ids.')) ORDER BY rank ASC';
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
  
      // prepare all groups with tag names
      $groups= array();
      foreach ($result as $item){
        $tagID= $item["tag"];
        $group_name= $item["group_name"];
        $tag_name= $item["tag_name"];
        $caption= $item["tag_name"];
        
        $groups[$group_name][]= array( "name"=>$tag_name, "tagID"=> $tagID);
      }
    
      // print groups and tags
      foreach ($groups as $groupName=>$group){
        echo "<br>".$groupName."<br>";
        echo '<ul class="navi-li">';

        foreach ($group as $item){
          $tagName= $item["name"];
          $caption= $groupName.':'.$tagName;
          $tagID= $item["tagID"];
          if (isset($this->searchFilters[$tagID])){
            echo '<li ><span class="ui-button ui-state-disabled">'.$tagName.'</span></li>';
          } else {
            echo '<li><a class="ui-button" href="?add_filter='.$tagID.'&filter_caption='.$caption.'">'.$tagName.'</a></li>';
          }
          //echo "</ul>";
            
        }
        echo "</ul>";
          
      }
      
        
  }
  
  function renderSearchTags(){
  
      $article_IDs= $this->foundArticles;
      
      if (empty($article_IDs)){
        return;
      }
      
      // lookup all suiting tags of articles
      $search= implode(",", $article_IDs);
      $sql= "SELECT `tag` FROM `gk_article_tags` WHERE `article_id` IN (".$search.") GROUP BY `tag`";
      $result = dbExecute( $sql );
      $tags= array();
      foreach( $result as $item){
        $tags[]= $item["tag"];
      }
      
      // lookup suiting full tag info for all tags
      $search= implode(",", $tags);
      $sql= "SELECT * FROM `gk_article_groups` WHERE `tag` IN (".$search.")";
      $result = dbExecute( $sql );
      
      $this->renderFullTags( $result );
    
  }

  function renderSearchFilters(){
    
    
        // --- 
    echo '<div id="div_search_filters">';
    if (is_array($this->searchFilters)){
      
      foreach ($this->searchFilters as $tagID=>$caption ){
        
        echo ' <span class="remove_filter">['.$caption.'<a href="?del_filter='.$tagID.'"><img src="./search/cross.png"></a>]</span> ';
        
      }
    }
    echo '</div>';

  }
  
  function renderPageLink( $pageNo,$caption="", $selected= false, $enabled= true ){
    
    if (empty($caption)){
      $caption= $pageNo;
    }
    
    $class= "searchPage";
    
    if ($selected){
      $class.= " selectedSearchPage";
    }
    
    if ($enabled==false){
      $class.= " not-active";
    }
    
    echo '<a class="'.$class.'" href="./?search_page='.($pageNo).'">'.($caption).'</a>';
  }
  
  function renderPageNumbers(){
   
    $maxPageIndex= ceil(count($this->foundArticles)/ITEM_COUNT_PER_PAGE)-1;
    if ($maxPageIndex <= 0){
        /* no page slider needed */
      return;
    }
  
    echo "<br>";
    
    if ($maxPageIndex < (LONG_PAGE_COUNT)){
      /* small page slider needed */
      //
      $this->renderPageLink( $this->searchPage - 1, "&lt;prev" );
              
      for ($i=0;$i<=$maxPageIndex;$i++){
        if ($i == $this->searchPage){
          $this->renderPageLink($i, ($i+1), true);
        } else {
          $this->renderPageLink($i, ($i+1));
        }
      } 

      $this->renderPageLink( $this->searchPage + 1, "&gt;next" );
      
    } else {
      /* large page slider needed */
      //
      $a= $this->searchPage - floor(LONG_PAGE_COUNT/2);
      
      if($a < 0){
        $a= 0;
      }
      $b= $a+ LONG_PAGE_COUNT - 1;
      if ($b >= ($maxPageIndex)){
        $b= $maxPageIndex;
        $a= $b - LONG_PAGE_COUNT+1;
      }


      if ($this->searchPage == 0){
        $enabled= false;
      } else { 
        $enabled= true;
      }        
      $this->renderPageLink( $this->searchPage - 1, "&lt;prev" , false, $enabled );

      for ($i=$a;$i<=$b;$i++){
        $this->renderPageLink( $i, $i+1, ($this->searchPage==$i)?true:false );
      }
      
      if ($this->searchPage >= $maxPageIndex){
        $enabled= false;
      } else { 
        $enabled= true;
      }        
      $this->renderPageLink( $this->searchPage + 1, "next&gt;", false, $enabled );
      
      
    }
      

    
  }
  
  
}

  // create the search engine object
  $searchEngine= new SearchEngine();
  
  
  // ---
  // decide if we have a new request or changed request
  $url_form_search_text = getUrlParam( "form_search_text" );
  
  if ($url_form_search_text != ""){
    // we have a new request
    $searchEngine->setSearchText($url_form_search_text);
  }

  // check for new filters
  $url_add_filter= getUrlParam("add_filter");
  if ($url_add_filter != ""){
    // we have a request to add a filter
    $url_caption= getUrlParam("filter_caption");
    $searchEngine->addFilter( $url_add_filter, $url_caption );
  }

  // check for removed filters
  $url_del_filter= getUrlParam("del_filter");
  if ($url_del_filter != ""){
    // we have a request to remove a filter
    $searchEngine->removeFilter( $url_del_filter );
  }

  // check for search page
  $url_search_page= getUrlParam("search_page");
  if ($url_search_page != ""){
    // we have a change in search page
    $searchEngine->setSearchPage( $url_search_page );
  }

  // just display all active filters
  $searchEngine->renderSearchFilters();
   
  // based on all new information, check if we need to execute a complete research
  $searchEngine->search();
    
  
  echo '<table>';
  echo '<tr><td>';
      echo '<div id="search_navi">';
      $searchEngine->renderSearchTags();
      echo '</div>';

  echo '</td><td>';
      echo '<div id="searchresult">';
      echo $searchEngine->searchResultText;
      $searchEngine->renderSearchResult();
      echo '</div>';
      
  echo '</td></tr>';
  echo '</table>';
  

?>
