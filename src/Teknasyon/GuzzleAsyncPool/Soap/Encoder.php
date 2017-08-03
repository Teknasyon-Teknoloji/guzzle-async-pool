<?php

namespace Teknasyon\GuzzleAsyncPool\Soap;

class Encoder extends \SoapClient
{
    /**
     * @var string
     */
    protected $xml;

    /**
     * @param $functionName
     * @param array $params
     * @param array|null $options
     * @param null $inputHeaders
     * @return string
     */
    public function encode(
        $functionName,
        array $params,
        array $options = null,
        $inputHeaders = null
    ) {
        $this->__soapCall($functionName, $params, $options, $inputHeaders);
        $xml = $this->xml;
        $this->xml = null;
        return $xml;
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->xml = $request;
        return '';
    }
}
