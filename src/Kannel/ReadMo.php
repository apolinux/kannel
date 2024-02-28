<?php 

namespace Apolinux\Kannel;

use Apolinux\Validator\Validator;
use stdClass;

class ReadMo{

  /**
   * @var Validator
   */
  private $validator ;

  private $clean_text ;
  private $clean_from ;
  private $clean_to ;

  private $param_obj = [
    'from' => 'from',
    'to' => 'to',
    'text' => 'text',
  ];

  public function __construct($param_obj=[],$clean_text=true, $clean_from=true, $clean_to=true){
    $this->param_obj = $param_obj; 
    $this->clean_text = $clean_text;
    $this->clean_from = $clean_from;
    $this->clean_to = $clean_to;
  }

  public function read($input=[]){
    $out = $this->parseInput($input);

    if($this->validator){
      if(! $this->validator->validate($input)){
        throw new ReadMoException($this->validator->getLastError());
      }
    }

    if($this->clean_text){
        $out->text = preg_replace('/[^ A-Za-z0-9]/','',$out->text);
    }

    if($this->clean_from){
      $out->from = preg_replace('/^\+/','',$out->from);
    }

    if($this->clean_to){
      $out->to = preg_replace('/^\+/','',$out->to);
    }

    return $out ;
  }

  public function setValidationRules($rules){
    $this->validator = new Validator($rules);
  }

  private function parseInput($input){
    $obj = new stdClass;

    foreach($this->param_obj as $item => $val){
      if(isset($input[$val])){
        $obj->$item = $input[$val];
      }
    }

    return $obj ;
  }
}