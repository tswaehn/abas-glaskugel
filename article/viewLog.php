<?php
  
  $filename="/tmp/sql.log";
  
  $contents=file_get_contents( $filename );
  
  echo "<pre>";
  echo $contents;
  echo "</pre>";

?>
