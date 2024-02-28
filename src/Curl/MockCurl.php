<?php 

namespace Apolinux\Curl;

use Apolinux\Curl\Response;
use ErrorException;

function custom_error_handler($errno, $errstr, $errfile, $errline) {
  // error was suppressed with the @-operator
  if (0 === error_reporting()) {
      return false;
  }
  if(preg_match('/curl/i',$errstr)){
    return true ;
  }
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}; 

class MockCurl{

  function getResponse($url,$data, $resp, $code, $message, $http_code, $total_time){
    set_error_handler('\\Apolinux\\Curl\\custom_error_handler');

    try{
      $cd = new DetailResponse(null, $resp);
      $cd->code     = $code ;
      $cd->message  = $message;
      $cd->info     = [
        'total_time' => $total_time,
        'http_code' => $http_code,
        'content-type' => '',
      ];
      $cd->response = $resp;
    }catch(ErrorException $e){

    }
    restore_error_handler();
    $fu = $url .'?' . http_build_query($data);
    return new Response($data,$resp,[],$cd,$fu);
  }

  function get($url, $data = null, $content_type = 'text/plain', ?float $timeout = null){
    $res_url=parse_url($url);
    
    if(isset($res_url['query'])){
      $data_query = $res_url['query'];
      parse_str($data_query, $data);
    }

    if(! isset($data['type'])){
      return $this->getResponse($url,$data,'type not defined', 0,'',500,0);
    }
    
    switch($data['type']){
        case 0 :
            return $this->getResponse($url,$data,'0: Accepted for delivery', 0,'',202,0);
    
        case 1 :
            return $this->getResponse($url,$data,'3: Queued for later delivery', 0,'',202,0);
    
        case 2 :
            return $this->getResponse($url,$data,'Input error', 0,'',401,0);

        case 3:
            return $this->getResponse($url,$data,'Temporal failure, try again later.', 0,'',503,0);
        case 4:
            return $this->getResponse($url,$data,'', 28,'Timeout',0,5);
    
    }
  }
}