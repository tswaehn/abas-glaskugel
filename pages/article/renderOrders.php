<?php
/*
  function renderFertigung( $article ){
    div("fertigung");
    disp('<span id="caption">Fertigung</span><br>');
    disp( "Einkaufstext ".$article["ebez"] );
    //disp( "Dispositionsart ".$article["dispo"] );
    disp( "Beschaffung ".$article["bsart"] );
    disp( "Lieferant ".$article["ynlief"] );
    
    // create two col layout
    disp('<table><tr>');
    
    disp("<td>");
      
      echo "<pre>";
      $result = getAktuelleFertigungsParents( $article["nummer"] );
      
      foreach ($result as $item){
	
	disp( $item["nummer"]." ".$item["tename"] );
      
      }
    
      echo "</pre>";
    disp("</td>");
    
    disp("<td>");
    $result = getAktuelleFertigungsliste( $article["nummer"] );
    
    disp("aktuell zu fertigende Elemente");
    echo '<table id="fertigungsliste">';
    foreach ($result as $item){
      $line = "<tr>";
      
      $line .= "<td>".$item["tabnr"]."</td>";
      $line .= "<td>".$item["elem"]."</td>";
      //$line .= "<td>".$item["elart"]."</td>";
      $line .= "<td>".$item["elarta"]."</td>";
      $line .= "<td>".$item["elname"]."</td>";
      $line .= "<td>".$item["anzahl"]."</td>";
      //$line .= "<td>".$item["elle"]."</td>";
    
      $line .="</tr>";
      
      echo $line;
    }
    echo "</table>";
    disp("</td>");
    disp("</tr></table>");
    ediv();
  }
*/
  function renderEinkaufBestellung($article){

    div("", "articleview");
    disp('<span id="caption">Offene Bestellung</span><br>');
    $article_id = $article["article_id"];
/*    
    echo '<div id="bestellung-ajax"></div>';
    
    $updateUrl = "ajax.php?action=bestellung&abas_nr=".$article["nummer"];
    $tag="bestellung-ajax";
    insertUpdateScript( $updateUrl, $tag, $cyclic = 0 );
*/
    ajaxRenderBestellung($article_id);
    
    ediv();
  }  
  
  
  function ajaxRenderBestellung($article_id=""){ 
    
    if (empty($article_id)){
      $article_id = getUrlParam("article_id");
    }

    
    // join orders with articles to get required info
    //
    // SELECT d1.*,d0.nummer,d0.betreff,d0.aumge,d0.planmge,d0.such FROM 
    //		(SELECT * FROM `orders` WHERE article_id=12028) AS d0 
    //		INNER JOIN `article` AS d1 
    //		ON d0.nummer=d1.article_id 
    //		ORDER BY nummer
    //
    $sql = "SELECT * FROM ".q(DB_ORDERS)." WHERE article_id=".$article_id;
    $result = dbExecute( $sql );
    
    if ((!empty($result)) && ($result->rowCount() > 0)){
      echo '<table class="sortable">';

      echo "<tr><th>Bestellnummer</th><th>Lieferant</th><th>Bedarf</th><th>Offen</th><th>VPE</th><th>Liefer- termin</th></tr>";
      foreach ($result as $part ){

	echo "<tr>";
	echo "<td>".$part["nummer"]."</td>";
	echo "<td>".$part["such"]."</td>";    
	echo "<td>".$part["bedmge"]."</td>";
	echo "<td>".$part["limge"]."</td>";
	echo "<td>".$part["vpe"]."</td>";
	echo "<td>".$part["term"]."</td>";
        echo "</tr>";
      }
      disp( "</table>" ); 
    }
  
  
  }

?>
