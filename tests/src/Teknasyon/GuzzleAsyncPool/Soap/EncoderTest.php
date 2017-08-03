<?php

namespace Teknasyon\GuzzleAsyncPool\Soap;

use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{
    public function testEncode()
    {
        $wsdlPath = realpath(dirname(__DIR__)) . '/test.wsdl';
        $functionName = 'Add';
        $params = [
            'Add' => [
                'intA' => 10,
                'intB' => 20
            ]
        ];
        $encoder = new Encoder($wsdlPath);
        $xml = $encoder->encode($functionName, $params);
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
        $this->assertEquals($expectedXml, $xml);
    }
}
