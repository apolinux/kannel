<?php

use Apolinux\Kannel\ReadMo;
use PHPUnit\Framework\TestCase;

class ReadMoTest extends TestCase{
  public function testSendMoOk(){
    //$this->assertTrue(false);
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ]);

    $input = [
      'from' => '1234',
      'to' => '9876',
      'text' => 'ehlo uorld',
    ];
    $parsed = $mo->read($input);
    $this->assertEquals('1234', $parsed->from);
    $this->assertEquals('9876',$parsed->to);
    $this->assertEquals('ehlo uorld',$parsed->text);
  }

  public function testSendMoValidateOk(){
    //$this->assertTrue(false);
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ]);

    $input = [
      'from' => '3123456789',
      'to' => '9876',
      'text' => 'ehlo uorld',
    ];

    $mo->setValidationRules([
      'from' => 'regex:/3\d{9}/',
      'to' => 'regex:/\d{4,}/',
      'text' => 'regex:/\w+/'
    ]);

    $parsed = $mo->read($input);
    $this->assertEquals('3123456789', $parsed->from);
    $this->assertEquals('9876',$parsed->to);
    $this->assertEquals('ehlo uorld',$parsed->text);
  }

  public function testSendMoCleanTextOk(){
    //$this->assertTrue(false);
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ],true);

    $input = [
      'from' => '3123456789',
      'to' => '9876',
      'text' => '?[ehlo $u#oñr<ld',
    ];

    $mo->setValidationRules([
      'from' => 'regex:/3\d{9}/',
      'to' => 'regex:/\d{4,}/',
      'text' => 'regex:/\w+/'
    ]);

    $parsed = $mo->read($input);
    $this->assertEquals('3123456789', $parsed->from);
    $this->assertEquals('9876',$parsed->to);
    $this->assertEquals('ehlo uorld',$parsed->text);
  }

  public function testSendMoNoCleanText(){
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ],false);

    $input = [
      'from' => '3123456789',
      'to' => '9876',
      'text' => '?[ehlo $u#oñr<ld',
    ];

    $mo->setValidationRules([
      'from' => 'regex:/3\d{9}/',
      'to' => 'regex:/\d{4,}/',
      'text' => 'regex:/\w+/'
    ]);

    $parsed = $mo->read($input);
    $this->assertEquals('3123456789', $parsed->from);
    $this->assertEquals('9876',$parsed->to);
    $this->assertEquals('?[ehlo $u#oñr<ld',$parsed->text);
  }

  public function testSendMoCleanFromTo(){
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ],false);

    $input = [
      'from' => '+3123456789',
      'to' => '+9876',
      'text' => '?[ehlo $u#oñr<ld',
    ];

    $mo->setValidationRules([
      'from' => 'regex:/\+3\d{9}/',
      'to' => 'regex:/\+\d{4,}/',
      'text' => 'regex:/\w+/'
    ]);

    $parsed = $mo->read($input);
    $this->assertEquals('3123456789', $parsed->from);
    $this->assertEquals('9876',$parsed->to);
    $this->assertEquals('?[ehlo $u#oñr<ld',$parsed->text);
  }

  public function testSendMoNoCleanFromTo(){
    $mo = new ReadMo([
      'from' => 'from',
      'to' => 'to', 
      'text' => 'text',
    ],false,false,false);

    $input = [
      'from' => '+3123456789',
      'to' => '+9876',
      'text' => '?[ehlo $u#oñr<ld',
    ];

    $mo->setValidationRules([
      'from' => 'regex:/\+3\d{9}/',
      'to' => 'regex:/\+\d{4,}/',
      'text' => 'regex:/\w+/'
    ]);

    $parsed = $mo->read($input);
    $this->assertEquals('+3123456789', $parsed->from);
    $this->assertEquals('+9876',$parsed->to);
    $this->assertEquals('?[ehlo $u#oñr<ld',$parsed->text);
  }
}