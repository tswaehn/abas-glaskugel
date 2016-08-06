<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$path= pathinfo( __FILE__ )["dirname"];

$config_dev= $path."/config-dev.txt";
$config= $path."/config.txt";

if (file_exists($config_dev)){
  include($config_dev);
} else {
  include($config);
}
