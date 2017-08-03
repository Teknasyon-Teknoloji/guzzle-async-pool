<?php

namespace Teknasyon\GuzzleAsyncPool\Soap;

class Decoder extends \SoapClient
{
    protected $xml;

    public function __construct()
    {
        parent::__construct(null, array('location' => '1', 'uri' => '2'));
    }

    /**
     * @param $xml
     * @return mixed
     */
    public static function decode($xml)
    {
        $decoder = new Decoder();
        $decoder->xml = $xml;
        return $decoder->pseudoCall();
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        return $this->xml;
    }
}
