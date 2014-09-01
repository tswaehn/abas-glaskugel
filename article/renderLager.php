<?php
  
  function renderBestand( $article ){
  
    $einheit = " ". $article["ve"] ;
    $bestand = "Bestand -";
    
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
    div("artikel");
    disp('<span id="caption">Lager</span><br>');
    disp( "Ein-/Ausgang ".$article["zuplatz"]."/".$article["abplatz"] );
    
    $bestand = renderBestand( $article );
    disp( $bestand );
    
    ediv();
  
  }
  
  
?>
