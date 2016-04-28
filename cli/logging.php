<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("ERROR_FILE", "errors.log");

  // global storage for back trace
  $errorBackTrace= "";
  
function errorInit(){
  file_put_contents( ERROR_FILE, "\n---".date("r")."\n" );
}

/*
 * this function describes the current step that is beeing
 * executed.
 * in case of an error this backtrace will be logged to file
 */
function backTrace( $text ){
  global $errorBackTrace;
  
  $errorBackTrace= $text;  
}


function error( $function, $message ){
  global $errorBackTrace;
  
  $line= $errorBackTrace."\n";
  $line.= $function.">".$message."\n";
  file_put_contents( ERROR_FILE, $line, FILE_APPEND );
  
}