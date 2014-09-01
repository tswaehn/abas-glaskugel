<?php
  
  // note: this is UTF-8
  $edp_conf = '

[Teil:Artikel]
fieldlist=nummer,name,such,sucherw,erfass,stand,zeichen,ebez,bsart,ynlief,zuplatz,abplatz,ypdf1,ydxf,yxls,ytpdf,ytlink,bild,bbesch,foto,fotoz,catpics,catpicsz,catpicl,catpiclz,caturl,zn,tabnr,anzahl,elanzahl,elart,elarta,elem,elex,bestand,lgbestand,zbestand,dbestand,lgdbestand,ve,fve,versionn,yzeissnr,zeichn,yersteller,lief,lief2,zoll,wstoff,bem,kenn,bstnr,ftext,vbezbspr,vkbezbspr
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Teil:Zugänge/Abgänge]
fieldlist=id,nummer,such
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Arbeitsgang:Arbeitsgang]
fieldlist=id,nummer,such,name,aschein,hinweis
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Bestellung]
fieldlist=id,nummer,such,betreff,art,artex,artikel,tename,ls,re,aumge,planmge,bem,ysenddat,ysendusr,lief
sortby=nummer
maxdatasize=100000
byrows=1
sortasc=1
search-and=1

[Fertigungsliste:Fertigungsliste]
fieldlist=id,nummer,artikel,anzahl,elem,elart,elarta,elle,zid,tabnr
sortby=nummer
maxdatasize=100000
byrows=1
sortasc=1
search-and=1

[Betr-Auftrag:Betriebsaufträge]
fieldlist=id,nummer,artikel,anzahl,elanzahl,mge,tabnr,zn,erfass,stand,aezeichen,ykomplatz
sortby=nummer
maxdatasize=100000
byrows=1
sortasc=1
search-and=1


[Teil:Fertigungsmittel]
fieldlist=nummer,such,name,platz
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

';
  
  function createEDPini(){
    global $edp_conf;
    // create config for EDPConsole
    $ini_filename = "EDP.ini";
    // the external program reads the ini-file as ANSI thus convert it into ANSI
    $edp_conf = iconv( "UTF-8", "Windows-1252", $edp_conf );
    file_put_contents( $ini_filename, $edp_conf );
  }
    
  class EDPImport {
    
    public $tablename;
    public $searches;
    
    function __construct( $name ){
      $this->tablename= $name;
      $this->fields=array();
      $this->searches=array();
    
    }
    
    function addSearch( $search ){
      
      $this->searches[] = $search;
    
    }
  }

  // -----------------------------------------------------------------
  //
  $teil_artikel = new EDPImport( "Teil:Artikel" );
  
  $teil_artikel->addSearch("nummer=0!0000-00000");    
  $teil_artikel->addSearch("nummer=0000-00000!1999-99999");  
  $teil_artikel->addSearch( "nummer=2000-00000!2199-99999" );  
  $teil_artikel->addSearch( "nummer=2200-00000!2399-99999" );  
  $teil_artikel->addSearch( "nummer=2400-00000!2599-99999" );  
  $teil_artikel->addSearch( "nummer=2600-00000!2799-99999" );  
  $teil_artikel->addSearch( "nummer=2800-00000!2999-99999" );  
  $teil_artikel->addSearch( "nummer=3000-00000!3999-99999" );  
  $teil_artikel->addSearch( "nummer=4000-00000!4999-99999" );  
  $teil_artikel->addSearch( "nummer=5000-00000!5999-99999" );  
  $teil_artikel->addSearch( "nummer=6000-00000!6999-99999" );  
  $teil_artikel->addSearch( "nummer=7000-00000!7999-99999" );  
  $teil_artikel->addSearch( "nummer=8000-00000!8999-99999" );  
  $teil_artikel->addSearch( "nummer=9000-00000!9999-99999" );  
 
  // -----------------------------------------------------------------
  //
  $teil_zug_abg = new EDPImport( "Teil:Zugänge/Abgänge" );
  
  $teil_zug_abg->addSearch("nummer=");    

  // -----------------------------------------------------------------
  //
  $teil_fertigungsmittel = new EDPImport( "Teil:Fertigungsmittel" );
  
  $teil_fertigungsmittel->addSearch("nummer=");    
 
  // -----------------------------------------------------------------
  //
  $einkauf_bestellung = new EDPImport( "Einkauf:Bestellung" );
  $einkauf_bestellung->addSearch("id=");      

  // -----------------------------------------------------------------
  //
  $fertigungs_liste = new EDPImport( "Fertigungsliste:Fertigungsliste" );
  $fertigungs_liste->addSearch("id=;flistestd=ja");

  // -----------------------------------------------------------------
  //
  $betr_auftraege = new EDPImport( "Betr-Auftrag:Betriebsaufträge" );
  $betr_auftraege->addSearch("id=");
 
  // -----------------------------------------------------------------
  //
  $inventur = new EDPImport( "Inventur:Zähllistenkopf" );
  $inventur->addSearch("id=");

  // -----------------------------------------------------------------
  //
  $arbeitsgang = new EDPImport( "Arbeitsgang:Arbeitsgang" );
  $arbeitsgang->addSearch("id=");
  
  // -----------------------------------------------------------------
  //
  // ...
  
  
  
  // -----------------------------------------------------------------
  //
  function getEDPDefinition(){
    global $teil_artikel;
    global $einkauf_bestellung;
    global $fertigungs_liste;
    global $betr_auftraege;
    global $teil_zug_abg;
	global $teil_fertigungsmittel;
	global $inventur;
	global $arbeitsgang;
	
    $import = array(
      
	    $teil_artikel,
	    $fertigungs_liste,
		$teil_zug_abg,
		$teil_fertigungsmittel,
		$arbeitsgang,

	    $betr_auftraege,
	    $einkauf_bestellung,

		$inventur
	    
	  );
	  /*
    $import = array(
		$einkauf_bestellung,

		);
*/
	return $import;
  }


?>
