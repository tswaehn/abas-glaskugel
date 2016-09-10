<?php
  
  echo "<pre>";
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );

  if (isset( $url_file )){
    $filename = urldecode($url_file);
    
    $filename = utf8_decode( $url_file );  // php file access is always ISO-8859-1 

    if(file_exists($filename)) {

	$output_name = pathinfo( $filename,  PATHINFO_BASENAME );
	//$output_name = pathinfo( $filename,  PATHINFO_FILENAME );
	$output_name .= '.jpg';
	
	if (isset($info["extension"])){
	  $ext = $info["extension"];
	  
	  if (in_arrayi( $ext, $mediaThumbnail)){
	    $images[] = $item;
	  }
	}
	
	
	      $img = new imagick(); // [0] can be used to set page number
	      $img->setResolution(90,90);
	      $img->readImage($filename.'[0]');
	      $img->setImageFormat( "jpeg" );
	      $img->setImageCompression(imagick::COMPRESSION_JPEG); 
	      $img->setImageCompressionQuality(90); 

	      $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
	      
	      $img->__toString();
	      //$img = $img->flattenImages();
	      //$img->writeImage('./pageone.jpg'); 
	      
	      
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$output_name);
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . $img->getImageLength() );

	if (ob_get_length()){
		ob_clean();
	}
	
	flush();
	
	echo $img;
	

	//$data = $img->getImageBlob(); 
	$img->clear();
	$img->destroy();

	exit;
      
    } else {
      die( "file does not exist" );
    }
  } else {
    sleep(5);
    die( "invalid file given" );
  }
    
   
  echo "</pre>";
?>
