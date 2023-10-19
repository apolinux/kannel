<?php 

namespace Apolinux\Kannel;

use Apolinux\Validator\Validator;

/**
 * validate MO
 * 
 * not ready yet
 * 
 * @see https://www.kannel.org/download/1.4.5/userguide-1.4.5/userguide.html#AEN4069
 * 
 * @author apolinux
 */
class ReadMo{

  const MAP_FIELDS_DEFAULT = [
    'from' => 'from',
    'to'   => 'to'  ,
    'text' => 'text' ,
  ];

  private $rules ;

  private $result ;

  private $map_fields = self::MAP_FIELDS_DEFAULT ;

  public function __construct($rules=[], $map_fields=self::MAP_FIELDS_DEFAULT)
  {
    $this->rules      = $rules ;
    $this->map_fields = $map_fields ;
  }

  /**
   */
  public function validate($request, $filter_fields=true)  {
    $validator = new Validator($this->rules);

    $is_valid = $validator->validate($request);

    if(! $is_valid){
      throw new KannelException($validator->getLastError());
    }

    if($filter_fields){
      // filter fields
      $this->filterFields($request);
    }
  }

  private function filterFields(&$request){
    $text_field_name = $this->map_fields['text'];
    $this->filterText($request, $text_field_name);
  }

  private function filterText(&$request, $field_name){
    if(! isset($request[$field_name])){
      return ;
    }
    // allow only number and letters and
    // remove many spaces
    $request[$field_name] = preg_replace(
      ['/[^0-9A-Za-z ]/', '/\s{2,}/'],  
      [''               , ' '       ],  
      $request[$field_name]);

  }
}