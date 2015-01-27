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

    div("EinkaufBestellung", "articleview");
    disp('<span id="caption">Einkauf:Bestellung(Stammdaten)</span><br>');
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
    $sql = "SELECT d1.*,d0.nummer,d0.betreff,d0.aumge,d0.planmge,d0.such FROM (SELECT * FROM ".q(DB_ORDERS)." WHERE article_id=".$article_id.") AS d0 INNER JOIN ".q(DB_ARTICLE)." AS d1 ON d0.article_id ORDER BY nummer";
    $result = dbExecute( $sql );
    
    disp( "");
    
    if (!empty($result)){
      disp( "<table>" );

      foreach ($result as $part ){

	//disp( $item["bem"]." ".$item["nummer"]." ".$item["such"]." ".$item["betreff"]." ".$item["art"]." ".$item["planmge"]." ".$item["aumge"]." ".$item["tename"] );
	echo "<tr>";
	echo "<td>".$part["nummer"]."</td>";
	echo '<td><a href="?action=article&article_id='.$part["article_id"].'">'.$part["nummer"]."</td>";
	switch ($part["elem_type"]){
	  case 1: echo "<td>Artikel</td>";break;
	  case 3: echo "<td>Arbeitsschritt</td>";break;
	  default: echo "<td>unbekannt</td>";break;
	}
	echo "<td>".$part["such"]."</td>";    
	echo "<td>".$part["ls"]."</td>";
	echo "<td>".$part["lief"]."</td>";
	echo "<td>".$part["re"]."</td>";	
	echo "<td>".renderBestand( $part)."</td>";	
	
	echo "</tr>";
      }
      disp( "</table>" ); 
    }
  
  
  }

?>
