<?php
	
	// nummer,such,sucherw,name,bild,ypdf1,ytlink,ytpdf
	function printSingleArticle( $line ){
		
		$fields = explode( '#', $line );
		if (sizeof($fields) > 8){
			//print_r($fields);
			$abasNr = $fields[1];
			$suchwort = $fields[2];
			$sucherw = $fields[3];
			$name = $fields[4];
			$bild = $fields[5];
			$pdf = $fields[6];
			$link = $fields[7];
			$pdf2 = $fields[8];
			
			echo "<div id='article'>";
			echo $abasNr.' '.$suchwort.'<br>';
			echo $sucherw.'<br>';
			echo "</div>";
		} else {
			$line = preg_replace( '/,/', ' ', $line );
			echo "<div id='article'>";
				echo $line.'<br>';
			echo "</div>";
		
		}
	}
	
	function printArticles( $output ){
	
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $output) as $line){
			if (strpos($line, 'DATA>') !== FALSE){
				printSingleArticle( $line );
			} else {
				// ignore
			}
			
		} 	


	}	
		
	if (isset($_GET["abas"])){
		
		$search = $_GET["abas"];
		
		$output = shell_exec( 'EDPConsole Teil:Artikel nummer='.$search );
		
		printArticles( $output );
		
		echo '<pre>';
		echo $output;
		echo '</pre>';
	} else {
		echo '...';
	}
?>