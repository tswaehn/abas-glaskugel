<?php
/*
 * Created on 16.01.2010
 * file showRemoteInfos.php
 * part of OpenImageShop_news
 * 
 * by tswaehn (http://sourceforge.net/users/tswaehn/)
 */
 
  $clientInfo = "";
  
  function addClientInfo( $str ){
    global $clientInfo;
    
    $clientInfo.=$str.";";
    
  
  }
  
  function getRemoteIP(){

	  if (isset($_SERVER['HTTP_X_REMOTE_ADDR'])) {
	    $ip = $_SERVER['HTTP_X_REMOTE_ADDR'];
	  } else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	  }
	  
	  return $ip; 	
  }

  

  function dbAddClientAccess( $ip, $host, $info = "", $duration ){
    
    $table = DB_CLIENT_ACCESS;
    
    // INSERT INTO table ( `id` ,`timestamp` ,`ip` ,`desc`) 
    //		VALUES (NULL ,CURRENT_TIMESTAMP , '".$ip."', NULL);";
    $sql = "INSERT INTO ".q($table)." ( `timestamp`, `ip`, `host`, `info`, `request_time` ) ";
    $sql.= "VALUES ( CURRENT_TIMESTAMP, '".$ip."', '".$host."', '".$info."', ".$duration." );";
      
    dbExecute( $sql );
    
  
  }
  
  function getRemoteInfos( $duration ){
    
    if (isset($_SERVER['HTTP_X_REMOTE_ADDR'])) {
      $ip = $_SERVER['HTTP_X_REMOTE_ADDR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    $host = gethostbyaddr($ip);
    
    // package infos
    $info["ip"] = $ip;
    $info["host"] = $host;
    
    global $clientInfo;
    
    dbAddClientAccess( $ip, $host, $clientInfo, $duration );
    
    return $info;    
  }
 

 
?>
 
