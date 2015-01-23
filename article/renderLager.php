<?php
  
  function renderBestand( $article ){
  
    $einheit = " ". $article["ve"] ;
    $bestand = "Bestand - verfügbar";
    
    if ($article["dbestand"] != $article["lgdbestand"]){
      $bestand .= " ".$article["dbestand"].$einheit;
      $bestand .= ", intern: ".$article["lgdbestand"].$einheit;
      $bestand .= ", extern: ". ($article["dbestand"] - $article["lgdbestand"]).$einheit;
      
    } else {
      $bestand .= " ".$article["dbestand"].$einheit;
    }

    if ($article["zbestand"] != 0){
      $bestand .= ", zugeteilt: ".$article["zbestand"].$einheit;
    }
    
    if ($article["bestand"] != $article["dbestand"]){
      if ($article["bestand"] != $article["lgbestand"]){
	$bestand .= " ges: ".$article["bestand"].$einheit;
	$bestand .= ", intern: ".$article["lgbestand"].$einheit;	
      } else {
	$bestand .= " ges: ".$article["bestand"].$einheit;
      }
    }
    
    return $bestand;
  
  }
  
  function renderLager( $article ){
    div("lager");
    disp('<span id="caption">Lager</span><br>');
    //disp( "Ein-/Ausgang ".$article["zuplatz"]."/".$article["abplatz"] );
    
    $bestand = renderBestand( $article );
    disp( $bestand );
    $article_id= $article["article_id"];
    
    $sql = "SELECT lemge,such,lgruppe,lager,dispo,name FROM ".q(DB_STORAGE)." WHERE article_id=".$article_id.";";
    $result = dbExecute( $sql );
    
    if (!empty($result)){
      out('<table>');
      out('<tr><th>Menge</th><th>Platz</th><th>Gruppe</th><th>Type</th><th>Dispo</th><th>Name</th></tr>');
      
      foreach($result as $item ){
        $out='<tr>';  
        $out.= '<td>'.$item["lemge"].'</td>';
        $out.= '<td>'.$item["such"].'</td>';
        $out.= '<td>'.$item["lgruppe"].'</td>';
        $out.= '<td>'.$item["lager"].'</td>';
        $out.= '<td>'.$item["dispo"].'</td>';
        $out.= '<td>'.$item["name"].'</td>';

        $out.='</tr>';
        out( $out );
      }


      out('</table>');
    }
    ediv();
  
  }
  
  
?>
