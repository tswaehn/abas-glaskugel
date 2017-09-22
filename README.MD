
...::::::
          A B A S -- G l a s K u g e l
				       :::::::....
      
      
    
offene Punkte


  - Fertigungsliste: Arbeitsgänge und Fertigungsmittel mit anzeigen

  - Doppelte Medien sollten vermieden werden: 7500-65401 (gleichzeitig ein Pfad+ein Bildlink)
  - "2058-17090" bg-msu hat zwei stücklisten

  - import von arbeitsgängen
  - import von interner fertigung aus "Betr-Auftrag:Betriebsaufträge"

2018 ???.?? - v0.2.8
  - added "Stromlaufplan"
  - 

2017 sep.22 - v0.2.7
  - Wording in Verwendungsliste verbessert
  - bestaetigter Liefertermin bei Bestellungen 
  - leichte Performance Verbesserungen
  - Links in Medien sind nun klickbar
  - Anzeige von Vorgaenger und Nachfolger 
  - Suche mit Bestand

2017 may.03 - v0.2.6
  - thumbnails in Fertigungsliste
  - Designanpassungen
   

2016 aug
  - anstelle Artikelbezeichnung die Verkaufsbezeichnung, wenn vorhanden
    - Bezeichnung
    - Verkauf deutsch
    - Verkauf englisch
  - thumbnails
  - search tags
  - reduzieren des standard logs
  - zeilenendezeichen vom log
  - reduzieren der thumbnail erzeugung
  - URL check in artikel anhängen

2015 feb.?? - v0.2.5
  - darstellung von bestellinformationen
  - im kopf wird "suchen" und "artikel" gleichzeitig und immer angezeigt
  - zeige "auf" / "ab" pfeilchen in sortierbaren tabellen

2015 jan.23 - v0.2.4
  - deutliche reduzierung der EDP import zeit von 3h auf 15min!!
  - article_IDs werden aus artikel-nummern berechnet. hyper-links auf artikel 
    bleiben ab jetzt für immer erhalten und können auch per email verschickt werden.
  - rendern von lager ist angepasst => spalte gruppe entfernt
  - fertigungsliste mit überschriften
  - lagerliste ist mit klick auf überschrift sortierbar
  - fertigungsliste ist mit klick auf überschrift sortierbar
  - einfluss von "flistestd" in "Fertigungsliste:Fertigungsliste" unklar => es wird immer die aktuelle stammdaten fertigungsliste angezeigt
  - lagerdetails werden angezeigt
  - Text nach @@ entfernen; sucherw bereinignen, ggf. andere felder benutzen und aus namen ausschneiden

2014 mar.25 - v0.2.3
  - Anzeige von Statistik 
  - Browser detect
  
2014 mar.24 - v0.2.2
  - Anzeige des letzten Sync Zeitpunkts

2014 mar.16 - v0.2.1
  - Bei der Eingabe der Suche/Artikel Nummer ggf. trimmen oder filtern "fatal error: .... on line 85"
  - [db-update] dokufeld für pdf fehlt: Feld “bbesch“ bsp. 4500-00222 W:\Datenblaetter\7400-00067_Datasheet_LP67.pdf  
  - datum und letzte änderung von artikeln korrekt angezeigt  
  - Artikel zeigt Kennzeichen im Klartzext an (X S L N)
  - Felder im Artikel ergänzen: bem, ken, bstnr, ftext, vbezbspr, vkbezbspr
  - Im Dateinamen von PDFs wird nach dem Leerzeichen abgeschnitten
 
  

2014 mar.11 - v0.2.0
  - Angezeigte „Such“ Sekunden sinnvoll runden => Ausführungszeit eingekürzt auf 3Stellen nach dem Komma
  - Beispieltext für Suche eingefügt
  - Suchbegriff „bnc kabel“ entfernen
  - Beim klicken auf das PDF sollte sich das orig. PDF öffnen.  
  - fix: Leerzeichen in Medien funktionieren nicht : 4500-00075
  - fix: Umlaute in medien : 7500-65101 ( http://tank/abas/?action=article&article_id=21787 )
  - release notes und build nr 
 
2014 feb. 23 - v0.0.0
  fixed
    - EDP import blockiert aktuell an "für" in "name" bzw. "sucherw"
    - Dispo falsch interpretiert
