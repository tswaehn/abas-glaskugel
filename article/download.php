<?php
  
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );

  if (isset( $url_file )){
    $filename = urldecode($url_file);
    
    $filename = utf8_decode( $url_file );// php file access is always ISO-8859-1 
    
    if(file_exists($filename)) {
  
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($filename).'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($filename));

	if (ob_get_length()){
		ob_clean();
	}
	flush();
	readfile($filename);
	exit;

    } else {
      die( "file does not exist" );
    }
  } else {
    sleep(5);
    die( "invalid file given" );
  }
?>
