
<div id="title">
  <?php echo $title ?>
</div>

<div id="nav">
  <table width="100%">
  <colgroup>
    <col width="1*">
    <col width="1*">
  </colgroup>
  
  <tr><td>
	
	
    <span id="menu"><a href="?action=search" >Suchen<a></span>
    <span id="menu"><a href="?action=article" >Artikel<a></span>
	
  </td><td style="text-align:right">
  
  <?php
    if (defined("_EN_OVERDRIVE_")){
      echo '<a href="?action=overdrive">..::oVerdRive::..<a>';
    }
    ?>
    
    <a href="?action=raw">°°raw°°<a>
    
  </td></tr>
  </table>

</div>

