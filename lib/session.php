<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  function getSessionVar( $name ){
    global $_SESSION;
    
    if (isset($_SESSION[$name])){
      return $_SESSION[$name];
    } else {
      return NULL;
    }
  }
  
  function setSessionVar( $name, $value ){
    global $_SESSION;
    
    $_SESSION[$name]= $value;
  }
  
  session_start();
  lg(print_r($_SESSION, true));
