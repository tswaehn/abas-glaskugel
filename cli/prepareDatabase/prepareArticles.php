<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
   * We assume there is a table called "Teil:Artikel"
   * now we take all "interesting" columns an re-order/import
   * them to our working table. The final tablename is defined
   * by DB_ARTICLE. 
   *
   */
  function dbCreateTableArticle(){
  
    $table = DB_ARTICLE;
    
    backTrace("dbCreateTableArticle");
    
    if (tableExists( $table ) == true ){
      removeTable( $table );
    }
    $new_table_fields = array( "article_id", "rank", "nummer", "such", "name", "ebez", 
		     "bsart", "ynlief", "zuplatz", "abplatz",  
		     "bestand", "lgbestand", "zbestand", "dbestand", "lgdbestand", 
		     "ve", "fve",
		     "zeichn","lief","lief2","yersteller","yzeissnr",
		     
		     "ypdf1", "ydxf", "yxls", "ytpdf", "ytlink", "bild", "bbesch", "foto",
		     "fotoz", "catpics", "catpicsz", "catpiclz", "caturl",
		     
		     "erfass", "stand", "zeichen",
		     
		     "bem", "kenn", "bstnr", "vkbezbspr", "ftext",
        
                     "thumbnail",
        
                     "vkbez", "vbezbspr", "vkbez2", "vbez2", "zoll", "ycatsale", "ersatzt", "verschlt", "mpr", "vpr9", "urland", "urregion",
    		 "yersatza", "yprepart",
        
		     );
    
    $fieldinfo=array();
    
    $fieldinfo["article_id"]["type"]=INDEX;
    $fieldinfo["article_id"]["size"]=0;

    $fieldinfo["rank"]["type"]=INT;
    $fieldinfo["rank"]["size"]=0;
    
    $fieldinfo["nummer"]["type"]=ASCII;
    $fieldinfo["nummer"]["size"]=15;

    $fieldinfo["such"]["type"]=ASCII;
    $fieldinfo["such"]["size"]=30;

    $fieldinfo["name"]["type"]=ASCII;
    $fieldinfo["name"]["size"]=255;
    
    $fieldinfo["ebez"]["type"]=ASCII;
    $fieldinfo["ebez"]["size"]=38;
    
    $fieldinfo["bsart"]["type"]=ASCII;
    $fieldinfo["bsart"]["size"]=16;
    
    $fieldinfo["ynlief"]["type"]=ASCII;
    $fieldinfo["ynlief"]["size"]=8;

    $fieldinfo["zuplatz"]["type"]=ASCII;
    $fieldinfo["zuplatz"]["size"]=15;

    $fieldinfo["abplatz"]["type"]=ASCII;
    $fieldinfo["abplatz"]["size"]=15;

    $fieldinfo["bestand"]["type"]=FLOAT;
    $fieldinfo["bestand"]["size"]=0;

    $fieldinfo["lgbestand"]["type"]=FLOAT;
    $fieldinfo["lgbestand"]["size"]=0;

    $fieldinfo["zbestand"]["type"]=FLOAT;
    $fieldinfo["zbestand"]["size"]=0;

    $fieldinfo["dbestand"]["type"]=FLOAT;
    $fieldinfo["dbestand"]["size"]=0;

    $fieldinfo["lgdbestand"]["type"]=FLOAT;
    $fieldinfo["lgdbestand"]["size"]=0;
    
    $fieldinfo["ve"]["type"]=ASCII;
    $fieldinfo["ve"]["size"]=6;
        
    $fieldinfo["fve"]["type"]=FLOAT;
    $fieldinfo["fve"]["size"]=0;

    $fieldinfo["zeichn"]["type"]=ASCII;
    $fieldinfo["zeichn"]["size"]=22;

    $fieldinfo["lief"]["type"]=ASCII;
    $fieldinfo["lief"]["size"]=8;

    $fieldinfo["lief2"]["type"]=ASCII;
    $fieldinfo["lief2"]["size"]=8;
    
    $fieldinfo["yersteller"]["type"]=ASCII;
    $fieldinfo["yersteller"]["size"]=20;
    
    $fieldinfo["yzeissnr"]["type"]=ASCII;
    $fieldinfo["yzeissnr"]["size"]=15;
    
    $fieldinfo["ypdf1"]["type"]=ASCII;
    $fieldinfo["ydxf"]["type"]=ASCII;    
    $fieldinfo["yxls"]["type"]=ASCII;
    $fieldinfo["ytpdf"]["type"]=ASCII;    
    $fieldinfo["ytlink"]["type"]=ASCII;
    $fieldinfo["bild"]["type"]=ASCII;    
    $fieldinfo["bbesch"]["type"]=ASCII;
    $fieldinfo["foto"]["type"]=ASCII;    
    $fieldinfo["fotoz"]["type"]=ASCII;
    $fieldinfo["catpics"]["type"]=ASCII;    
    $fieldinfo["catpicsz"]["type"]=ASCII;
    $fieldinfo["catpiclz"]["type"]=ASCII;    
    $fieldinfo["caturl"]["type"]=ASCII;        

    $fieldinfo["erfass"]["type"]=ASCII;    
    $fieldinfo["stand"]["type"]=ASCII;     
    $fieldinfo["zeichen"]["type"]=ASCII;     

    $fieldinfo["bem"]["type"]=ASCII;     
    $fieldinfo["kenn"]["type"]=ASCII;     
    $fieldinfo["bstnr"]["type"]=ASCII;     
    $fieldinfo["vkbezbspr"]["type"]=ASCII;     
    $fieldinfo["ftext"]["type"]=ASCII;     
    $fieldinfo["thumbnail"]["type"]=ASCII;

    $fieldinfo["vbezbspr"]["type"]=ASCII;     
    $fieldinfo["vkbez"]["type"]=ASCII;
    $fieldinfo["vkbez2"]["type"]=ASCII;
    $fieldinfo["vbez2"]["type"]=ASCII;
    $fieldinfo["zoll"]["type"]=ASCII;
    $fieldinfo["ycatsale"]["type"]=ASCII;
    $fieldinfo["ersatzt"]["type"]=ASCII;
    $fieldinfo["verschlt"]["type"]=ASCII;
    $fieldinfo["mpr"]["type"]=ASCII;
    $fieldinfo["vpr9"]["type"]=ASCII;
    $fieldinfo["urland"]["type"]=ASCII;
    $fieldinfo["urregion"]["type"]=ASCII;
    
    $fieldinfo["yersatza"]["type"]=ASCII;
    $fieldinfo["yersatza"]["size"]=15;
    $fieldinfo["yprepart"]["type"]=ASCII;
    $fieldinfo["yprepart"]["size"]=15;
    
    createTable( $table, $new_table_fields, $fieldinfo );

    $copy_fields = array( "nummer", "such", "name", "ebez", 
		     "bsart", "ynlief", "zuplatz", "abplatz", 
		     "bestand", "lgbestand", "zbestand", "dbestand", "lgdbestand", 
		     "ve", "fve", "zeichn", "lief", "lief2", "yersteller", "yzeissnr",
		     "ypdf1", "ydxf", "yxls", "ytpdf", "ytlink", "bild", "bbesch", "foto",
		     "fotoz", "catpics", "catpicsz", "catpiclz", "caturl",
		     "erfass", "stand","zeichen",
		     "bem", "kenn", "bstnr", "vkbezbspr", "ftext",
		     
                     "vbezbspr", "vkbez", "vkbez2", "vbez2", "zoll", "ycatsale", "ersatzt", "verschlt", "mpr", "vpr9", "urland", "urregion",
    		 "yersatza", "yprepart",
		     );

    // get all entries which need to be copied
    $fieldStr = "`".implode( "`,`", $copy_fields )."`";
    
    $sql = "SELECT ".$fieldStr." FROM `Teil:Artikel` WHERE 1 ";
    $result = dbExecute($sql);
    
    // this is mainly because we need to calculate the articleID from "nummer"
    $dataSet= array();
    global $botList;
    foreach ($result as $item){
      $output=array();
      // set article_id
      $articleID= str_replace( "-", "", $item["nummer"] );
      $output[]= $articleID;
      
      //save last editor
      if (!in_array($item['zeichen'], $botList)) {
      	//no bot -> safe 'zeichen' (editor name) and 'stand' (edit time)
      	$sql = 'INSERT INTO '.DB_LASTEDIT.' VALUES ('.$articleID.', "'.$item['zeichen'].'", "'.$item['stand'].'") '.
      		   ' ON DUPLICATE KEY UPDATE editor="'.$item['zeichen'].'", edittime="'.$item['stand'].'"';
      	dbExecute($sql);
      }
      	
      
      // transfer all known fields
      foreach ($copy_fields as $field){
        $output[]= $item[$field];
      }
      
      // add to dataSet
      $dataSet[]= $output;
    }

    // add the article_id to the field list
    $fields= array_merge( array("article_id"), $copy_fields );
    
    // finally we put the big table into our database
    insertIntoTable( $table, $fields, $dataSet );

    report("imported ".count($dataSet)." articles successfully");
  }

?>
