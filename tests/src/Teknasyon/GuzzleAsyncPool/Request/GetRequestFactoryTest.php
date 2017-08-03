<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class GetRequestFactoryTest extends TestCase
{
    public function testFactory()
    {
        $url = 'http://teknasyon.com';
        $params = ['q' => 'test'];
        $request = GetRequestFactory::factory($url, $params);
        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('q=test', $request->getUri()->getQuery());
        $this->assertEquals('teknasyon.com', $request->getUri()->getHost());
    }
}
