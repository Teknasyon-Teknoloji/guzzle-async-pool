<?php

namespace Teknasyon\GuzzleAsyncPool\Request;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class PostRequestFactoryTest extends TestCase
{
    public function testFactory()
    {
        $url = 'http://teknasyon.com';
        $params = ['q' => 'test'];
        $request = PostRequestFactory::factory($url, $params);
        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('teknasyon.com', $request->getUri()->getHost());
        $this->assertEquals('q=test', $request->getBody()->getContents());
    }
}
