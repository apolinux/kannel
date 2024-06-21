<?php 

namespace Apolinux\Kannel ;

use Apolinux\Curl\Response;

class SendMtResponse{
  /**
   * HTTP code
   * @var int
   */
  public $http_code ;

  /**
   * text plain response
   * @var string
   */
  public $text ;

  /**
   * response object
   * @var Response
   */
  public $response ;

  /**
   * duration of request
   * @var float
   */
  public $duration ;

  /**
   * full URL requested
   * @var string
   */
  public $fullurl ;
  
  /**
   * __construct
   *
   * @param  Response $curl
   */
  public function __construct(Response $response, string $fullurl)
  {
    $this->response = $response ;
    $this->text = $this->response->response ;
    $this->http_code = $response->http_code ;
    $this->duration = $response->duration ;
    $this->fullurl = $fullurl;
  }
}