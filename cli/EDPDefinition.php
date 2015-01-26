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


[Lager:Lagergruppe]
fieldlist=id,nummer,such,name 
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Lmenge:Lagermenge]
fieldlist=id,artikel,tename,lager,platz,lgruppe,lemge,lab,lzu
sortby=id
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Lplatz:Lagerplatzkopf]
fieldlist=id,nummer,such,name,lager,lgruppe,dispo,stand
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Lager:Lager]
fieldlist=id,nummer,such,name
sortby=
maxdatasize=10000
byrows=0
sortasc=1
search-and=1


[Einkauf:Einkauf]
fieldlist=nummer,such, id, vorgang, erfass, kterm, term, tterm
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Fertigungsvorschlag]
fieldlist=artikel, id, art, bsart, nummer, artex
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Position]
fieldlist=id, artikel, gruppe, kotyp, tename
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Reservierungen]
fieldlist=artikel, artex, einplan, elart, elem, nummer, such, id, platz, tterm, twterm
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Zahlungen]
fieldlist=gruppe,id, budat, zbeldat
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Arten:Real]
fieldlist=gruppe,id, nummer, such, stand, erfass
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Auftrags-STL:Auftrags-STL]
fieldlist=gruppe,id, nummer, such, stand, erfass
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Lager:Lagergruppeneigenschaft]
fieldlist=id, artikel, art, grust, gruppe, grbez, dbez 
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Offener Posten]
fieldlist=gruppe,grust, dbez, id, nummer, term, tterm
sortby=nummer
maxdatasize=100000
byrows=0
sortasc=1
search-and=1

[Einkauf:Rahmanauftrag]
fieldlist=id, term, plz, grust, gruppe, artikel, platz
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
  $teil_artikel->addSearch("nummer=0000-00000!1199-99999");  
  $teil_artikel->addSearch( "nummer=1200-00000!1399-99999" );  
  $teil_artikel->addSearch( "nummer=1400-00000!1599-99999" );  
  $teil_artikel->addSearch( "nummer=1600-00000!1799-99999" );  
  $teil_artikel->addSearch( "nummer=1800-00000!1999-99999" );   
 
  $teil_artikel->addSearch( "nummer=2000-00000!2199-99999" );  
  $teil_artikel->addSearch( "nummer=2200-00000!2399-99999" );  
  $teil_artikel->addSearch( "nummer=2400-00000!2599-99999" );  
  $teil_artikel->addSearch( "nummer=2600-00000!2799-99999" );  
  $teil_artikel->addSearch( "nummer=2800-00000!2999-99999" );  
  
  $teil_artikel->addSearch( "nummer=3000-00000!3199-99999" );
  $teil_artikel->addSearch( "nummer=3200-00000!3399-99999" );  
  $teil_artikel->addSearch( "nummer=3400-00000!3599-99999" );  
  $teil_artikel->addSearch( "nummer=3600-00000!3799-99999" );  
  $teil_artikel->addSearch( "nummer=3800-00000!3999-99999" );
  
  $teil_artikel->addSearch( "nummer=4000-00000!4199-99999" );
  $teil_artikel->addSearch( "nummer=4200-00000!4399-99999" );  
  $teil_artikel->addSearch( "nummer=4400-00000!4599-99999" );  
  $teil_artikel->addSearch( "nummer=4600-00000!4799-99999" );  
  $teil_artikel->addSearch( "nummer=4800-00000!4999-99999" );
  
  $teil_artikel->addSearch( "nummer=5000-00000!5199-99999" );
  $teil_artikel->addSearch( "nummer=5200-00000!5399-99999" );  
  $teil_artikel->addSearch( "nummer=5400-00000!5599-99999" );  
  $teil_artikel->addSearch( "nummer=5600-00000!5799-99999" );  
  $teil_artikel->addSearch( "nummer=5800-00000!5999-99999" );
    
  $teil_artikel->addSearch( "nummer=6000-00000!6199-99999" );  
  $teil_artikel->addSearch( "nummer=6200-00000!6399-99999" );  
  $teil_artikel->addSearch( "nummer=6400-00000!6599-99999" );  
  $teil_artikel->addSearch( "nummer=6600-00000!6799-99999" );  
  $teil_artikel->addSearch( "nummer=6800-00000!6999-99999" );
  
  $teil_artikel->addSearch( "nummer=7000-00000!7199-99999" );
  $teil_artikel->addSearch( "nummer=7200-00000!7399-99999" );  
  $teil_artikel->addSearch( "nummer=7400-00000!7599-99999" );  
  $teil_artikel->addSearch( "nummer=7600-00000!7799-99999" );  
  $teil_artikel->addSearch( "nummer=7800-00000!7999-99999" );
  
  $teil_artikel->addSearch( "nummer=8000-00000!8199-99999" ); 
  $teil_artikel->addSearch( "nummer=8200-00000!8399-99999" );  
  $teil_artikel->addSearch( "nummer=8400-00000!8599-99999" );  
  $teil_artikel->addSearch( "nummer=8600-00000!8799-99999" );  
  $teil_artikel->addSearch( "nummer=8800-00000!8999-99999" );
  
  $teil_artikel->addSearch( "nummer=9000-00000!9199-99999" );
  $teil_artikel->addSearch( "nummer=9200-00000!9399-99999" );  
  $teil_artikel->addSearch( "nummer=9400-00000!9599-99999" );  
  $teil_artikel->addSearch( "nummer=9600-00000!9799-99999" );  
  $teil_artikel->addSearch( "nummer=9800-00000!9999-99999" );
  
 
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
  $lager = new EDPImport( "Lager:Lager" );
  $lager->addSearch("id=");
  
  // -----------------------------------------------------------------
  //
  
  $lager_lagergruppe = new EDPImport( "Lager:Lagergruppe" );
  $lager_lagergruppe->addSearch("id=");
  
  // -----------------------------------------------------------------
  //
  
  $lmenge_lagermenge = new EDPImport( "Lmenge:Lagermenge" );
  $lmenge_lagermenge ->addSearch("id=;pflag=ja");
  
  // -----------------------------------------------------------------
  //
  
   $lplatz_lagerplatzkopf = new EDPImport( "Lplatz:Lagerplatzkopf" );
   $lplatz_lagerplatzkopf ->addSearch("id=");
  // -----------------------------------------------------------------
  //
   
   $einkauf = new EDPImport( "Einkauf:Einkauf" );
   $einkauf ->addSearch("id=");
       
   // -----------------------------------------------------------------
  //
   $einkauf_fertigungsvorschlag = new EDPImport( "Einkauf:Fertigungsvorschlag" );
   $einkauf_fertigungsvorschlag ->addSearch("id=");
       
   // -----------------------------------------------------------------
  //    
      
   $einkauf_position = new EDPImport( "Einkauf:Position" );
   $einkauf_position ->addSearch("id=");
  
   // -----------------------------------------------------------------
  //    
      
   $einkauf_reservierungen = new EDPImport( "Einkauf:Reservierungen" );
   $einkauf_reservierungen ->addSearch("id=");
  // -----------------------------------------------------------------
  //    
      
   $einkauf_zahlungen = new EDPImport( "Einkauf:Zahlungen" );
   $einkauf_zahlungen ->addSearch("id=");
       
  // -----------------------------------------------------------------
  //   
   $arten_real = new EDPImport( "Arten:Real" );
   $arten_real ->addSearch("id=");   
   
  // -----------------------------------------------------------------
  //   
  
   $auftrags_STL = new EDPImport( "Auftrags-STL:Auftrags-STL" );
   $auftrags_STL ->addSearch("id=");   
   
  // -----------------------------------------------------------------
  //   

  $lager_lagergruppeneigenschaft = new EDPImport( "Lager:Lagergruppeneigenschaft" );
  $lager_lagergruppeneigenschaft->addSearch("id=");  
  
  // -----------------------------------------------------------------
  //  
  
  $einkauf_offener_posten = new EDPImport( "Einkauf:Offener Posten" );
  $einkauf_offener_posten ->addSearch("id=");
      
  // -----------------------------------------------------------------
  //  
  
  $einkauf_rahmanauftrag = new EDPImport( "Einkauf:Rahmanauftrag" );
  $einkauf_rahmanauftrag ->addSearch("id=");
  
  
  function getEDPDefinition(){
    
	global $teil_artikel;
        global $fertigungs_liste;
        global $lager;
        global $lmenge_lagermenge;
        global $lager_lagergruppe;
        global $lplatz_lagerplatzkopf;
        global $einkauf;
        global $einkauf_bestellung;
        global $einkauf_fertigungsvorschlag;
        global $einkauf_position;
	global $einkauf_reservierungen;
        global $einkauf_zahlungen;
        global $arten_real;
        global $auftrags_STL;
        global $lager_lagergruppeneigenschaft;
        global $einkauf_offener_posten;
        global $einkauf_rahmanauftrag;
        
    $import = array(
        
            $teil_artikel,
            $fertigungs_liste,
            $lager,
            $lmenge_lagermenge,
            $lager_lagergruppe,
            $lplatz_lagerplatzkopf,
        /*
            $einkauf,
       //einkauf_fertigungsvorschlag
        $einkauf_position,
        $auftrags_STL,
        $einkauf_reservierungen,
        $einkauf_zahlungen,
        $arten_real,
       $lager_lagergruppeneigenschaft,
        $einkauf_offener_posten,
        $einkauf_rahmanauftrag,
        $einkauf_bestellung,
        */
        );
    
	return $import;
  }
?>
