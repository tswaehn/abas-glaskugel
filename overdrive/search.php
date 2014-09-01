


<?php

  
  $search = getUrlParam( "search");
  
  if ( empty( $search )){
    $search="bnc kabel";
  }
  
  echo '<div id="searchform">';
  echo '<form action="?action=overdrive&" method="POST">
		  <input type="edit" name="searchbox" value="'.$search.'" size="60" onkeyup="showResult(this.value)" autocomplete="off" >
		  <input type="submit" value="search">
		  </form>';  

  echo '<div id="livesearch"></div>';
  echo '</div>';

 
  
  
?>


<script>
function showResult(str)
{
 
if (str.length==0)
  {
  document.getElementById("livesearch").innerHTML="";
  document.getElementById("livesearch").style.border="0px";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
xmlhttp.open("GET","./overdrive/livesearch.php?q="+encodeURIComponent(str)+"&len="+str.length,true);
xmlhttp.send();
}
</script>
