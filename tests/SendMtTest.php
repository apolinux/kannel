<?php


use Apolinux\Kannel\SendMt;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase ;

class KannelTest extends PHPUnit_Framework_TestCase{

    private $port = 8000 ;
    private $url = 'http://localhost';

    public function setUp():void {
        parent::setUp();
        $this->url = "$this->url:$this->port";
        //$response = file_get_contents($this->url) ;
        $curl = curl_init("$this->url?type=0");
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $this->assertEquals(202,$info['http_code'],
        'must be enable a web server to do the test. Example: php -S localhost:8000 -t ./tests/server');
    }

    public function testSendMtOk(){
        $kannel = new SendMt;
        $response = $kannel->send(
          "$this->url?type=0", 
          'test',
          'test' ,
          '12345' ,
          '5678' ,
          'hello',
          'bla' ,
          [],
          3
        );

        $this->assertEquals(0, $response->response->curl->code);
        $this->assertEquals(202 , $response->response->http_code,'error:'. print_r($response,1));
        $this->assertEquals('0: Accepted for delivery', $response->text);
    }

    public function testSendMtErrorQueued(){
        $kannel = new SendMt;
        $response = $kannel->send(
          "$this->url?type=1", 
          'test',
          'test' ,
          '12345' ,
          '5678' ,
          'hello',
          'bla' ,
          [],
          3
        );

        $this->assertEquals(0, $response->response->curl->code);
        $this->assertEquals(202 , $response->response->http_code,'error:'. print_r($response,1));
        $this->assertEquals('3: Queued for later delivery', $response->text);
    }

    public function testSendMtError401(){
      $kannel = new SendMt;
      $response = $kannel->send(
        "$this->url?type=2", 
        'test',
        'test' ,
        '12345' ,
        '5678' ,
        'hello',
        'bla' ,
        [],
        3
      );

        $this->assertEquals(0, $response->response->curl->code);
        $this->assertEquals(401 , $response->response->http_code,'error:'. print_r($response,1));
        $this->assertEquals('Input error', $response->text);
    }

    public function testSendMtError503(){
      $kannel = new SendMt;
      $response = $kannel->send(
        "$this->url?type=3", 
        'test',
        'test' ,
        '12345' ,
        '5678' ,
        'hello',
        'bla' ,
        [],
        3
      );

        $this->assertEquals(0, $response->response->curl->code);
        $this->assertEquals(503 , $response->response->http_code,'error:'. print_r($response,1));
        $this->assertEquals('Temporal failure, try again later.', $response->text);
    }

    /*public function testSendMtParamsError(){
        $kannel = new Kannel;
        $this->expectException(KannelException::class);
        $response = $kannel->sendMt("$this->url?type=3", [
          'from' => '12345' ,
          'to' => '5678' ,
          'text' => 'hello',
          'username' => 'test',
          'password' => 'test' ,
          //'smsc' => 'bla' ,
        ]);
    }*/

    public function testSendMtErrorTimeout(){
        $kannel = new SendMt;
      $response = $kannel->send(
        "$this->url?type=4", 
        'test',
        'test' ,
        '12345' ,
        '5678' ,
        'hello',
        'bla' ,
        [],
        1
      );

      $this->assertEquals(CURLE_OPERATION_TIMEOUTED, $response->response->curl->code);
      $this->assertEquals(0 , $response->response->http_code,'error:'. print_r($response,1));
      $this->assertEquals('', $response->text);
    }

    public function testSendMtMetaParams(){
      $kannel = new SendMt;
      $response = $kannel->send(
        "$this->url?type=0", 
        'test',
        'test' ,
        '12345' ,
        '5678' ,
        'hello',
        'bla' ,
        [
          'meta-data' => [
            'smpp' => [
              'system' => 'abcde',
              'version' => '3456',
            ]
          ]
        ],
        3
      );

      $this->assertEquals(0, $response->response->curl->code);
      $this->assertEquals(202 , $response->response->http_code,'error:'. print_r($response,1));
      $this->assertEquals('0: Accepted for delivery', $response->text);
      $this->assertStringContainsString(
        urlencode('?smpp?system=abcde&version=3456'), 
        $response->response->full_url
      );
  }
}
