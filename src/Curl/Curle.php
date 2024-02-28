<?php 
namespace Apolinux\Curl ;

use Apolinux\Curl\Curl as CurlA;

class Curle extends CurlA{

  public function __construct($curl_options=[]){
    parent::__construct($curl_options);
  }

  public function get($url, $data = null, $content_type = 'text/plain', ?float $timeout = null)
  {
    if (env('MOCK_CURL')){
      $mock = new MockCurl;
      return $mock->get($url,$data,$content_type. $timeout);
    }else{
      return parent::get($url,$data,$content_type. $timeout);
    }
    
  }
}