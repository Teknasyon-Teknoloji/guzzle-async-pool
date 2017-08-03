<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;
use Teknasyon\GuzzleAsyncPool\Soap\Encoder;

class SoapRequestFactory
{
    public static function factory(
        $wsdl,
        $endpoint,
        $soapAction,
        $functionName,
        array $params = [],
        $options = null,
        $headers = null
    ) {
        $encoder = new Encoder($wsdl);
        $xml = $encoder->encode($functionName, $params, $options, $headers);
        $requestHeaders = array(
            'SOAPAction' => $soapAction,
            'Content-Type' => 'text/xml; charset=utf-8',
            'Content-Length' => strlen($xml)
        );
        return new Request('POST', $endpoint, $requestHeaders, (string)$xml);
    }
}
