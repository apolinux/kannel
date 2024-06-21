<?php 

namespace Apolinux\Kannel ;

use Apolinux\Curl\Curle;

/**
 * send MT to Kannel server
 * 
 * send MT to Kannel server using HTTP GET, making a validation of minimal data.
 */
class SendMt{

  const HTTP_CODE_RESPONSE_OK           = 202 ;
  const HTTP_CODE_RESPONSE_TEMP_FAILURE = 503 ;
  const HTTP_RESPONSE_OK              = '0: Accepted for delivery' ;
  const HTTP_RESPONSE_DELIVERED_LATER = '3: Queued for later delivery' ;
  const HTTP_RESPONSE_TEMP_FAILURE    = 'Temporal failure, try again later.' ;

  /**
   * send MT to kannel server
   *
   * @param  string $url
   * @param  string $username
   * @param  string $password
   * @param  string $from
   * @param  string $to
   * @param  string $text
   * @param  string $smsc
   * @param  array  $params
   * @return SendMtResponse
   * @see https://www.kannel.org/download/1.4.5/userguide-1.4.5/userguide.html#AEN5157
   */
  public function send(string $url, string $username, string $password, string $from, string $to, string $text, string $smsc=null, array $params = [], float $timeout=60){
    $params_mod = $this->validateInput($params);

    $full_params = [
      'username' => $username ,
      'password'  => $password ,
      'from'      => $from ,
      'to'        => $to ,
      'text'      => $text ,
    ];
    if(! empty($smsc)){
      $full_params['smsc'] = $smsc ;
    }

    $full_params = array_merge($full_params, $params_mod) ;

    $full_url = $url . '?' . http_build_query($full_params);
    
    $curl = new Curle();
    $response = $curl->get($full_url,null,'text/plain', $timeout);

    return new SendMtResponse($response, $full_url);
  }

  /**
   * validate input for meta-data field
   *
   * field of metadata must be encoded in special way when is sent to SMPP server.
   * example:
   * meta-data=?smsc_type?key1=value11&key2=value2 ...
   *  
   * metadata must come in array of arrays like this:
   * $request['meta_data'] => [
   * 'smsc_type' => [ // by default: 'smpp'
   *  'key1' => 'value1' ,
   *  'key2  => 'value2' ,
   * ...
   *  ] ,
   * 'smsc_type2' => [
   *  'key1' => 'value1' ,
   *  'key2  => 'value2' ,
   * ...
   *  ] ,  
   * ..
   *]
   * 
   * @param array $request 
   * @return array
   */
  private function validateInput($request){
    $request2 = $request;

    if(isset($request['meta-data'])){
        $meta_data=[] ;
        foreach($request['meta-data'] as $smsc_type => $item_list){
          foreach($item_list as $key => $value){
            //list($key, $value) = getKeyValue($item);
            $smsc_data[] = sprintf("%s=%s",$key, $value) ;
          }
          $meta_data[] = "?$smsc_type?" . join('&', $smsc_data) ;
        }
        $request2['meta-data'] = join('',$meta_data);
    }

    return $request2 ;
  }
}

function getKeyValue($array){
  $key = array_key_first($array);
  $item = $array[$key];
  return [$key, $item];
}

function getKeyValueAll($array){
  foreach($array as $key => $value){
    return [$key, $value];
  }
}
 