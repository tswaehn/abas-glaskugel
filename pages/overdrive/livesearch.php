<?php

  include('../../lib/lib.php');
  include('../../cli/dbConnection.php');
  include('./dbSearch.php');
  
  $query=getUrlParam( "q" );
  
  connectToDb();
 // -------------------
  // display all global available keys based on first entered word
  
  $sql = globalKeySearch( $query );
  $result = dbExecute( $sql );
  
  if (!empty($result)){
    $hit_count = $result->rowCount();
  
    $total_article_count=0;
    

    $c=0;
    $limit=300;
    $retStr="";
    foreach ($result as $item){
      $name=$item["str"];
      $count=$item["cnt"];
      $total_article_count += $count;
      
      $c++;
      //$retStr .= '[<a href="">-</a>]<a href="">'.$name."(".$count.")</a> ";
      $retStr .= $name.'<span style="font-size:small;color:gray">('.$count.")</span> ";
      if ($c>$limit){
	$retStr .= "<p>result limited, add more characters";
	break;
      }
    }
    
  
    echo $hit_count." Treffer mit insg. ".$total_article_count." Artikel(n)<p>";      
    echo $retStr;
  }

  echo "<p>";
  
  // -------------------
  // display filered keys based on first entered word
  $sql = filteredKeySearch( $query );
  $result = dbExecute( $sql );
  
  if (!empty($result)){
    
    $hit_count = $result->rowCount();
  
    $total_article_count=0;
    

    $c=0;
    $limit=300;
    $retStr="";
    foreach ($result as $item){
      $name=$item["str"];
      $count=$item["cnt"];
      $total_article_count += $count;
      
      $c++;
      //$retStr .= '[<a href="">-</a>]<a href="">'.$name."(".$count.")</a> ";
      $retStr .= $name.'<span style="font-size:small;color:gray">('.$count.")</span> ";
      if ($c>$limit){
	$retStr .= "<p>result limited, add more characters";
	break;
      }
    }
    
  
    echo $hit_count." Treffer mit ca. ".$total_article_count." Artikel(n)<p>";      
    echo $retStr;  
  }

  echo "<p>";
  
  // -------------------
  // display available search results based on all entered words
  
  $sql = articleSearchLike( $query );
  $result = dbExecute( $sql );
  
  if (!empty($result)){
    
    $retStr= $result->rowCount()." Artikel\n";
    
    $max=10;
    $i=0;
    
    $retStr .= "<table>";
    foreach ($result as $item ){
      
      $result=dbGetFromTable(DB_ARTICLE, array( "article_id", "nummer", "rank", "such" , "name"), "article_id='".$item["article_id"]."'" );
      
      $article = $result->fetch();
      
      $retStr .= "<tr><td>".$article["nummer"]." ".$article["rank"]."</td><td>".$article["such"]."</td><td>".$article["name"]."</td></tr>";    
      $i++;
      if ($i >= $max){
	$retStr .= "<tr><td> </td><td> </td><td>... und mehr</td></tr>";
	break;
      }
    }
    $retStr .= "</table>";    

    echo $retStr;
  
  }
  
  echo "</pre>";
  
  // -------------------
  
  
?>
