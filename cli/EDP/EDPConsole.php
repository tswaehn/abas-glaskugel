<?php

  function splitLine( $line ){
    $elements = preg_split( '/\x7F/', $line );
    // remove first elements
    array_shift( $elements );
    return $elements;
  }

  function stringsToArray( $contents ){

    $isFirst=1;
    $data=array( 'fields'=> array(), 'lines'=>array() );
    //$contents=preg_replace("/[^A-Za-z0-9\n#,>\-\ \ö\ä\ü\Ö\Ä\Ü\ß]/", '', $contents);
   
    // remove white spaces
    //$contents= preg_replace( array('/#\s+/','/\s+#/'), '#', $contents);
    
    //print_r($contents);
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){

      if (strpos($line, 'DATA>') !== FALSE){
	if ($isFirst){
	  $isFirst = 0;
	  $elem = preg_split( '/>/', $line );
	  $data['fields'] = preg_split( "/,/", $elem[1] );
	} else {
	  $data['lines'][] = splitLine( $line );
	}
	
      } else {
	      // ignore
      }
	    
    } 	

    return $data;
  }
  
  function renderData( $data ){
    $fields=$data['fields'];
    $lines=$data['lines'];
  
    $field_str='';
    foreach($fields as $field){
      $field_str .= $field. '  ';
    }
    debug( $field_str);

    
    foreach($lines as $line){
      $line_str='';  
      foreach($line as $elem){
	$line_str .= $elem. '  ';
      }
      debug( $line_str);  
    }
    
  }
  
  function executeEDP( $cmdLine ){
	
    // create local backup filename
    $filename='./data/edp-'.preg_replace("/[^A-Za-z0-9\ ]/", "-",$cmdLine).'.dat';
    $filename = preg_replace("/[^A-Za-z0-9\.\/\-]/", " ", $filename);

    debug("writing to file ".$filename );
    
    if (_REAL_EDP_ == 1){
      //$table = utf8_encode( $table );
      //$search = utf8_encode( $search );
      lg( "EDP exec :".$cmdLine);
	  
      // table and search are given in UTF8 
      shell_exec( "echo ".$cmdLine." > ./EDP/cmd_line.log");
	  
	  // the external programm has to convert from UTF8 to ANSI
      $contents = shell_exec( "cd ./EDP/ && ".$cmdLine ." 2>&1");
	  
	  // the return needs to be converted into UTF8
      $contents = utf8_encode( $contents );      

      // save the file as UTF8
      file_put_contents( $filename, $contents );
      
    } else {
      lg("EDPConsole - simulate" );
      $contents = file_get_contents( $filename );

      // the file is coded in UTF8
      //$contents = utf8_encode( $contents );            
    }
    
    
    return $contents;  	
   
  
  }
  
  function getEDPTables(){
  
    $table = "tables";
    $search = "*";
	
	$cmdLine = 'EDPConsole '.$table.' '.$search;
	
	executeEDP( $cmdLine );
    
  }
  
  function getEDPFieldNames( $table ){

    $par1 = "fieldnames";
    $par2 = $table;
	
    $cmdLine = 'EDPConsole '.$par1.' '.$par2 ; 

    $contents= executeEDP( $cmdLine );
	
    $data = stringsToArray( $contents );
    
    $lines = $data['lines'];
    $fieldnames = array();
    $fieldnames_str='';
    foreach ($lines as $line){
      $field = $line[0];
      
      switch ( $line[1] ){
	case 'A': $type = ASCII; break;
	case 'N': $type = FLOAT; break;
	case 'D': $type = ASCII; break; // fix: treat datetime as ASCII due to conversion problems
	default:
	  $type = $line[1] ; 
	  error( "getEDPFieldNames();", "unknown type ".$line[1] );
      }
      
      $size = $line[2];
      
      $fieldnames[$field] = array( 'type'=>$type, 'size'=>$size );
      $fieldnames_str .= ','.$line[0];
      
    }
//     
    debug( "fieldnames of ".$table." are ".count($fieldnames ) );
    debug( $fieldnames_str );
    return $fieldnames;
  }
  

  /*
      \return: big array with array for each entry
      
      $data['fields']= array('field1', 'field2', ... );
      $data['lines']= array(
			array( 'some', 'data', 'here' );
			array( 'some', 'other', 'data' );
		      )
  */
  function getEDPData( $table, $search ){

    lg( "table: ".$table );
    lg( "search: ".$search );

    $cmdLine = 'EDPConsole.exe '.$table.' '.$search;

    $contents=executeEDP( $cmdLine);
	  
    $data = stringsToArray( $contents );
    return $data;  	
  
  
  }


?>
