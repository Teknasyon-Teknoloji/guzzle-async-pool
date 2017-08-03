<?php

namespace Teknasyon\GuzzleAsyncPool\Soap;

use PHPUnit\Framework\TestCase;

class DecoderTest extends TestCase
{
    public function testArrayResponse()
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>'
            . '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'
            . '<soap:Body>'
            . '<TestResponse xmlns="http://tempuri.org/">'
                . '<firstName>name</firstName>'
                . '<lastName>surname</lastName>'
                . '<addresses>'
                    . '<address>Address 1</address>'
                    . '<address>Address 2</address>'
                . '</addresses>'
            . '</TestResponse>'
            . '</soap:Body>'
            . '</soap:Envelope>';
        $array = Decoder::decode($xml);
        $this->assertEquals('name', $array['firstName']);
        $this->assertEquals('surname', $array['lastName']);
        $this->assertInstanceOf(\stdClass::class, $array['addresses']);
        $this->assertEquals('Address 1', $array['addresses']->address[0]);
        $this->assertEquals('Address 2', $array['addresses']->address[1]);
    }

    public function testValueResponse()
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>'
            . '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'
            . '<soap:Body>'
            . '<TestResponse xmlns="http://tempuri.org/">'
                . '<price>10.2</price>'
            . '</TestResponse>'
            . '</soap:Body>'
            . '</soap:Envelope>';
        $price = Decoder::decode($xml);
        $this->assertEquals(10.2, $price);
    }
}
