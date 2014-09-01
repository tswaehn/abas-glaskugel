<?php

$fp = fsockopen("192.168.0.186", 15000, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
	echo "connected\n";
	
    $out = "7400-00000!7500-99999\r\n";

    fwrite($fp, $out);
	//stream_set_timeout( $fp, 20);
	/*
    while (!feof($fp)) {

        echo fgets($fp, 1024);
    }*/
	
	// Keep fetching lines until response code is correct
	while (1==1) {
	$line = fgets($fp);
    echo $line;
    $line = preg_replace('/\r\n/', '', $line );
//    $code = $line[0];
	if (strcmp( $line, 'exit') == 0) {
		break;
		}
	}
  
    fclose($fp);
}

/*
	$cmd = "EDPConsole.exe 7500-00000!7500-99999";
	
	$descriptorspec = array(
	   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	   2 => array("file", "error-output.txt", "a") // stderr is a file to write to
	);
	
	$pipes=array();
	$process = proc_open ( $cmd , $descriptorspec , $pipes );
	
	print_r( $descriptorspec );
	
	print_r( $pipes );

	if (is_resource($process)) {	

	    fwrite($pipes[0], 'exit\n');
		fclose($pipes[0]);

		echo stream_get_contents($pipes[1]);
		
		fclose($pipes[1]);
		
		$return_value = proc_close($process);
		echo "stopping ... ";
		echo $return_value;
		
	}
	
	echo "exit";
*/	
?>