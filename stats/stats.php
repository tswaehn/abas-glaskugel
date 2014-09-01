<?php
 
//  include('./stats/graph.php');
  
 
  function dbCreateClientAccess (){
    echo "<pre>";
    
    $table = DB_CLIENT_ACCESS;
    
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $new_table_fields = array( "id", "timestamp", "ip", "host", "info", "request_time" );
    
    $fieldinfo["id"]["type"]=INDEX;
    
    $fieldinfo["timestamp"]["type"]=TIMESTAMP;
    
    $fieldinfo["ip"]["type"]=ASCII;
    $fieldinfo["ip"]["size"]=50;
    
    $fieldinfo["host"]["type"]=ASCII;
    $fieldinfo["host"]["size"]=50;

    $fieldinfo["info"]["type"]=ASCII;
    $fieldinfo["info"]["size"]=50;

    $fieldinfo["request_time"]["type"]=FLOAT;
    
    createTable( $table, $new_table_fields, $fieldinfo );
      
    echo "</pre>";

  }
  

  function displayAccessByIP(){
    
    $table = DB_CLIENT_ACCESS;
    
    // ---- header
    $sql = "SELECT MIN(`timestamp`) AS st,MAX(`timestamp`) AS en FROM ".q($table)." WHERE 1";
    $result = dbExecute( $sql );
    if ($result->rowCount() > 0){
      $row=$result->fetch();
      echo "interval ".$row["st"]." to ".$row["en"]."<p>";
    }
    
    
    // ---- users per day
    $sql = "SELECT DATE(`timestamp`) as date,count(*) AS cnt FROM ".q($table)." WHERE 1 GROUP BY `date` ORDER BY `date` DESC";
    //$sql = "SELECT DATE(timestamp) as date,max(request_time) AS rmax, min(request_time) AS rmin, count(*) AS cnt FROM ".q($table)." WHERE 1 GROUP BY ip,DATE(timestamp) ORDER BY date DESC, ip ASC";
    $result = dbExecute( $sql );
    echo '<table id="stats">';
    echo "<tr>";
    	echo "<th> Date </th>";
	echo "<th> Access Count </th>";	
    echo "</tr>";
    
    if ($result->rowCount() > 0){
      foreach ($result as $item ){
	echo "<tr>";
	  echo "<td>".$item["date"]."</td>";
	  echo "<td>".$item["cnt"]."</td>";	
	echo "</tr>";
      
      }
    }
    echo "</table>";
    
    
    echo "<p>";
    
    
    // ---- table
    //$sql = "SELECT ip,host,info,count(*) as cnt FROM ".q($table)." WHERE 1 GROUP BY `ip`,`info` ORDER BY ip ASC, cnt DESC";
    $sql = "SELECT ip,host,DATE(timestamp) as date,max(request_time) AS rmax, min(request_time) AS rmin, count(*) AS cnt FROM ".q($table)." WHERE 1 GROUP BY ip,DATE(timestamp) ORDER BY date DESC, ip ASC";
    $result = dbExecute( $sql );

    echo '<table id="stats">';
    echo "<tr>";
    	echo "<th> Date </th>";
    	echo "<th> IP </th>";
	echo "<th> Host </th>";
	echo "<th> Access Count </th>";	
	echo "<th> Access Time </th>";	
    echo "</tr>";
    
    $date ="";
    if ($result->rowCount() > 0){
      foreach ($result as $item ){
	echo "<tr>";

	  // display one date for the day only
	  if ($item["date"] != $date){
	    $date = $item["date"];
	    echo "<td>".$item["date"]."</td>";
	  } else {
	    echo "<td></td>";
	  }
	  echo "<td>".$item["ip"]."</td>";
	  echo "<td>".$item["host"]."</td>";
	  echo "<td>".$item["cnt"]."</td>";	
	  echo "<td>".$item["rmin"]."/".$item["rmax"]."secs</td>";	
	  
	echo "</tr>";
      
      }
    }
    echo "</table>";
    
  }
  
  
  // ----------------
  
  
  echo "<p>";
  
  // link disabled
  //echo '<a href="?action=stats&create=1" >reset all</a>';

  $client = getUrlParam( "create" );
  
  if (!empty($client)){
    
    dbCreateClientAccess();
    
    echo "all history is cleared now<br>";
    
  }
   
  echo "<p>";
/*
  echo '<embed src="./stats/graph.php?type=byday" type="image/svg+xml" width="600" height="400"
  pluginspage="http://www.adobe.com/svg/viewer/install/" />';
*/    

  echo  '<object id="1" data="./stats/graph.php?type=byday" width="600" height="600" type="image/svg+xml" ></object>';

  echo  '<object id="2" data="./stats/graph.php?type=byuser" width="600" height="600" type="image/svg+xml" ></object>';
  echo "<br>";
  echo  '<object id="3" data="./stats/graph.php?type=time" width="600" height="600" type="image/svg+xml" ></object>';
  
  //graphByDay();
  //graphTopUsers();
  
  // table disabled
  //displayAccessByIP();


?>
