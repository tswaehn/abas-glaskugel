<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  define("LOG_FILE", "./logging/standard.log");
  define("ERROR_LOG", "./logging/error.log");
  
  // global storage for back trace
  $errorBackTrace= "";
  $logging= "";

function writeLogToDisk(){
  global $logging;
  
  file_put_contents( LOG_FILE, $logging );
}

/*
 * this function describes the current step that is beeing
 * executed.
 * in case of an error this backtrace will be logged to file
 */
function backTrace( $text ){
  global $errorBackTrace;

  $errorBackTrace= $text;  
  lg($text);
}

function lg( $text ){
  global $logging;
  
  $text .= "\n";
  
  $logging.= $text;
 // echo $text;
} 


function error( $function, $message ){
  global $errorBackTrace;
  
  $line= $errorBackTrace."\n";
  $line.= $function.">".$message."\n";
  
  lg($line);
  file_put_contents(ERROR_LOG, $line, FILE_APPEND);
}

function report( $text ){
  
  $text.= "\n";
  lg($text);
}

function debug( $text ){
  
  //$text.= "\n";

}
