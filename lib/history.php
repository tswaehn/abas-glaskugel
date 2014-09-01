<html>
<head>
  <title>..::Glaskugel - Release Notes::..</title>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  
</head>

</body>

<?php

  $text = file_get_contents( "../history.txt" );
  //$text = preg_replace("/\n/","<br>", $text);
  echo "<pre>";
  echo $text;
  echo "</pre>";

?>

</body>    
</html> 
