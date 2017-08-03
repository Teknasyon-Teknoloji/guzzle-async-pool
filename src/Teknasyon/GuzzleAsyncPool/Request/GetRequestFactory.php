<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;

class GetRequestFactory
{
    /**
     * @param $url
     * @param array $params
     * @return Request
     */
    public static function factory($url, array $params = [])
    {
        if (substr($url, -1) != '?') {
            $url.= '?';
        }
        $url.= http_build_query($params, null, '&');
        return new Request('GET', $url);
    }
}
