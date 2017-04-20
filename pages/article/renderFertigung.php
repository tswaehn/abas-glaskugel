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
  function renderFertingsliste($article){

    div("fertigungslisteX","articleview");
    disp('<span id="caption">Fertigungsliste / Stückliste (Stammdaten)</span><br>');
    $article_id = $article["article_id"];
/*    
    echo '<div id="fertigungsliste-ajax"></div>';
    
    $updateUrl = "ajax.php?action=fertigungsliste&abas_nr=".$article["nummer"];
    $tag="fertigungsliste-ajax";
    insertUpdateScript( $updateUrl, $tag, $cyclic = 0 );
*/
    ajaxRenderFertingsliste($article_id);
    
    ediv();
  }  
  
  
  function ajaxRenderFertingsliste($article_id=""){ 
    
    if (empty($article_id)){
      $article_id = getUrlParam("article_id");
    }
    
    
    // join production-list with articles to get required info
    //
    // SELECT d1.*,d0.elem_type,d0.cnt,d0.tabnr FROM 
    //		(SELECT * FROM `production_list` WHERE article_id=12028) AS d0 
    //		INNER JOIN `article` AS d1 
    //		ON d0.elem_id=d1.article_id 
    //		ORDER BY tabnr
    //
    $sql = "SELECT d1.*,d0.elem_type,d0.cnt,d0.tabnr FROM (SELECT * FROM ".q(DB_PRODUCTION_LIST)." WHERE article_id=".$article_id.") AS d0 INNER JOIN ".q(DB_ARTICLE)." AS d1 ON d0.elem_id=d1.article_id ORDER BY tabnr";
    $result = dbExecute( $sql );
    
    disp( "");
    
    if (!empty($result)){
      
      echo  '<table class="sortable">';
      echo "<tr><th>Pos</th><th>Bild</th><th>Nummer</th><th>Typ</th><th>Kurzname</th><th>Anzahl</th><th>Einheit</th><th>Bestand</th></tr>";
      
      foreach ($result as $part ){

	//disp( $item["zn"]." ".$item["tabnr"]." ".$item["anzahl"]." ".$item["elanzahl"]." ".$item["elart"]." ".$item["elarta"]." ".$item["elem"]." ".$item["elex"] );
	echo "<tr>";
	echo "<td>".$part["tabnr"]."</td>";
	echo '<td>'.showThumbnail($part, 50).'</td>';
	echo '<td><a href="?action=article&article_id='.$part["article_id"].'">'.$part["nummer"]."</td>";
	switch ($part["elem_type"]){
	  case 1: echo "<td>Artikel</td>";break;
	  case 3: echo "<td>Arbeitsschritt</td>";break;
	  default: echo "<td>unbekannt</td>";break;
	}
	echo "<td>".$part["such"]."</td>";    
	echo "<td>".$part["cnt"]."</td>";
	echo "<td>".$part["ve"]."</td>";
	//echo "<td>".$part["abplatz"]."</td>";	
	echo "<td>".renderBestand( $part)."</td>";	
	
	echo "</tr>";
      }
      disp( "</table>" ); 
    }
  
  
  }

?>
