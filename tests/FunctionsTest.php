<?php

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase{
  public function testEnvOk(){
    putenv('var1=abcde');
    $this->assertEquals('abcde',env('var1'));

    putenv('var2=123456789012');
    $this->assertEquals('123456789012',env('var2'));

    putenv('var1="abc"');
    $this->assertEquals('"abc"',env('var1'));
  }

  public function testEnvBad(){
    #putenv('var1=');
    $this->assertEquals('abcde',env('var3','abcde'));

    $this->assertNull(env('var4'));
    putenv('var1=');
    $this->assertEquals('',env('var1'));

    #putenv('var1="abc"');
    #$this->assertEquals('"abc"',env('var1'));
  }
}