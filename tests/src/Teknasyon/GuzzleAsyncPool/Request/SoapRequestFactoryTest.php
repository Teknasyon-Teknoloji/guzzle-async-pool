<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class SoapRequestFactoryTest extends TestCase
{
    public function testFactory()
    {
        $wsdlFile = realpath(dirname(__DIR__)) . '/test.wsdl';
        $endpoint = 'http://www.dneonline.com/calculator.asmx';
        $soapAction = 'http://tempuri.org/Add';
        $functionName = 'Add';
        $params = [
            'Add' => [
                'intA' => 10,
                'intB' => 20
            ]
        ];
        $request = SoapRequestFactory::factory($wsdlFile, $endpoint, $soapAction, $functionName, $params);
        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals('POST', $request->getMethod());
        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>'
            . PHP_EOL
            . '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://tempuri.org/">'
                . '<SOAP-ENV:Body>'
                    . '<ns1:Add>'
                        . '<ns1:intA>10</ns1:intA>'
                        . '<ns1:intB>20</ns1:intB>'
                    . '</ns1:Add>'
                . '</SOAP-ENV:Body>'
            . '</SOAP-ENV:Envelope>'
            . PHP_EOL;
        $this->assertEquals($expectedXml, $request->getBody()->getContents());
    }
}
