<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;

class PostRequestFactory
{
    /**
     * @param $url
     * @param array $params
     * @return Request
     */
    public static function factory($url, array $params = [])
    {
        return new Request(
            'POST',
            $url,
            array('Content-Type' => 'application/x-www-form-urlencoded'),
            http_build_query($params, null, '&')
        );
    }
}
