<html>
<head>
<link rel="stylesheet" type="text/css" href="format.css">
<link rel="stylesheet" type="text/css" href="./article/article.css">
<link rel="stylesheet" type="text/css" href="./stats/stats.css">
<link rel="stylesheet" type="text/css" href="./lib/sorttable.css">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<script src="./lib/sorttable.js"></script>
<script src="./js/jquery-3.1.0.min.js"></script>


<?php include('./lib/main.php'); ?>

<title>
..::Glaskugel::..
</title>
</head>

  <div id="head">
    
    <?php include('./lib/head.php'); ?>
    
  </div>

  <div id="search">
    <?php include('./search/search.php'); ?>
  </div>

  <div id="main">
    
    <?php include( $script ) ?>
    
  </div>
  
 
  
  <?php footer(); ?>
 
  <?php writeLogToDisk(); ?>
</html>
