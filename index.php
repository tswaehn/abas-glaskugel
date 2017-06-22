<html>
<head>
  
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  
  <link rel="stylesheet" type="text/css" href="./pages/article/article.css">
  <link rel="stylesheet" type="text/css" href="./pages/stats/stats.css">
  <link rel="stylesheet" type="text/css" href="./lib/sorttable.css">


  <script src="./lib/sorttable.js"></script>

  <link rel="stylesheet" type="text/css" href="./js/jquery-ui/jquery-ui.css">
  <script src="./js/jquery.js"></script>
  <script src="./js/jquery-ui/jquery-ui.js"></script>
   <script>
  $( function() {
    $( "input[type=submit], button" ).button();
  } );
  </script>
  <link rel="stylesheet" type="text/css" href="./css/format.css">  
  <!--[if IE]>
  <link rel="stylesheet" type="text/css" href="./css/ie.css">
  <![endif]-->
  
<?php include('./lib/main.php'); ?>

<title>
..::Glaskugel::..
</title>
</head>
<body>
  <div id="head">
    
    <?php include('./lib/head.php'); ?>
    
  </div>

  <div id="search">
    <?php include('./pages/search/searchForm.php'); ?>
  </div>

  <div id="main">
    
    <?php include( $script ) ?>
    
  </div>
  
 
  
  <?php footer(); ?>
 
  <?php writeLogToDisk(); ?>
</body>
</html>
