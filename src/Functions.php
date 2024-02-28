<?php 

if(! function_exists('env')){
  function env($name,$default=null){
    $val=getenv($name);
    if($val === false){
      return $default ;
    }
    return $val ;
  }
}
