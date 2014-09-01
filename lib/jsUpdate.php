<?php
 
  $ajaxId = 0;
  
  function insertUpdateScript( $updateUrl, $tag, $cyclic = 0 ){
  
  global $ajaxId;
  
  $ajaxId++;

  if ($cyclic){
    $timeout = 'setTimeout("ajax_call_'.$ajaxId.'()", 100);';
  } else {
    $timeout = '';
  }
  
  echo '<script type="text/javascript">

  	function addLoadEvent(func) {
	  var oldonload = window.onload;
	  if (typeof window.onload != "function") {
	    window.onload = func;
	  } else {
	    window.onload = function() {
	      if (oldonload) {
		oldonload();
	      }
	      func();
	    }
	  }
	}
	
	addLoadEvent(loaded_'.$ajaxId.');
	/*
	  window.onload=function(){
	  loaded_'.$ajaxId.'()
	};
	*/
	var updateTarget_'.$ajaxId.'="'.$tag.'";
	var xmlHttp_'.$ajaxId.' = null;
	var complete_'.$ajaxId.' = 1;
	
	try {
	    // Mozilla, Opera, Safari sowie Internet Explorer (ab v7)
	    xmlHttp_'.$ajaxId.' = new XMLHttpRequest();
	} catch(e) {
	    try {
		// MS Internet Explorer (ab v6)
		xmlHttp_'.$ajaxId.'  = new ActiveXObject("Microsoft.XMLHTTP");
	    } catch(e) {
		try {
		    // MS Internet Explorer (ab v5)
		    xmlHttp_'.$ajaxId.'  = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
		    xmlHttp_'.$ajaxId.'  = null;
		}
	    }
	}

	function loaded_'.$ajaxId.'(){

	  document.getElementById(updateTarget_'.$ajaxId.').innerHTML="loading data ...";
	  ajax_call_'.$ajaxId.'();

	}

	function ajax_call_'.$ajaxId.'() {
	
	  if (complete_'.$ajaxId.'==1){
	    complete_'.$ajaxId.'=0;
	  
	    xmlHttp_'.$ajaxId.'.open("GET", "'.$updateUrl.'", true);
	    xmlHttp_'.$ajaxId.'.onreadystatechange=function() { onAjaxCallDone_'.$ajaxId.'();  };
	    xmlHttp_'.$ajaxId.'.send(null);
	  }
	  '.$timeout.'
	      
	  return false;
	}

	function onAjaxCallDone_'.$ajaxId.'(){
	      
	    if (xmlHttp_'.$ajaxId.'.readyState==4) {
	      if (xmlHttp_'.$ajaxId.'.status == 200 || xmlHttp_'.$ajaxId.'.status == 400){
		document.getElementById(updateTarget_'.$ajaxId.').innerHTML=xmlHttp_'.$ajaxId.'.responseText;
		complete_'.$ajaxId.'=1;

	      } else {
		// ... do nothing
	      }
	    }
	}

	</script>';  
  
  
  
  }
  

?>
