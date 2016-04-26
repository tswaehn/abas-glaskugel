<?php
/*
 * Author:  Sven Ginka
 * email:   s.ginka@hseb-dresden.de
 * date:    01.feb.2014
 * 
 * 
 * ---
 * 
 * This PHP file addresses the post-processing after all data
 * had been imported from EDP.
 * 
 * As all data from EDP is mapped to exactly same fields we
 * need to convert them to a set of "Work-Tables" which are
 * optimized for lookup and final function set.
 * 
 * ---
 */  

  include('./prepareDatabase/prepareArticles.php');
  include('./prepareDatabase/prepareProductionList.php');
  include('./prepareDatabase/prepareStoragePlaces.php');
  include('./prepareDatabase/prepareDictionary.php');
  include('./prepareDatabase/prepareOrders.php');
  include('./prepareDatabase/prepareArticleThumbnails.php');


  /*
   * This function prepares a single table in where
   * configuration data can be stored.
   * 
   * If the configuration table is non existend it
   * will be created.
   * 
   */
  function setupConfigDb(){
    
    if (!tableExists(DB_CONFIG) ){
      $fields = array( "key", "value" );

      $fieldinfo["key"]["type"]=ASCII;
      $fieldinfo["key"]["size"]=30;
      $fieldinfo["key"]["additional"]='UNIQUE';
      $fieldinfo["value"]["type"]=ASCII;
      $fieldinfo["value"]["size"]=200;
      
      createTable( DB_CONFIG, $fields, $fieldinfo );
    }
    
  }
  
  /*
   * When we import new data, we put the database
   * on "lock".
   */
  function lockDb(){
    setConfigDb("dbLink", 0);
  }
  
  /*
   * This is the opposite of "lock" see lockDb();
   */
  function unlockDb(){
    setConfigDb("dbLink", 1);
  }
  

  function prepareDatabase(){
    
	
    //dbCreateTableArticle();
        /* * 
    dbCreateProductionList();
    dbCreateTableStoragePlaces();
    dbCreateDict();
    dbCreateOrders();
	*/
	dbCreateArticleThumbnails();
   
  }
 

  ?>
