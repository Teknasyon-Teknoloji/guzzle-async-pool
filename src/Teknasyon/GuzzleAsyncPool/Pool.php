<?php

namespace Teknasyon\GuzzleAsyncPool;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Pool
{
    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var array
     */
    protected $requests = [];

    /**
     * @var array
     */
    protected $guzzlePoolSettings = [];

    /**
     * @var \Closure
     */
    protected $onCompletedRequestCallback;

    /**
     * @var \Closure
     */
    protected $onFailedRequestCallback;

    /**
     * @param array $requests
     * @param array $guzzlePoolSettings
     * @param Client $guzzleClient
     */
    public function __construct(array $requests, array $guzzlePoolSettings, Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
        $this->requests = $requests;
        $this->guzzlePoolSettings = $guzzlePoolSettings;
        $this->guzzlePoolSettings['fulfilled'] = array($this, '_onCompleted');
        $this->guzzlePoolSettings['rejected'] = array($this, '_onFailed');
    }

    public function wait()
    {
        $pool = new \GuzzleHttp\Pool($this->guzzleClient, $this->requests, $this->guzzlePoolSettings);
        $promise = $pool->promise();
        $promise->wait();
    }

    /**
     * @param ResponseInterface $response
     * @param $index
     */
    public function _onCompleted($response, $index)
    {
        ($this->onCompletedRequestCallback)($index, $this->requests[$index], $response);
    }

    /**
     * @param \Exception $exception
     * @param $index
     */
    public function _onFailed(\Exception $exception, $index)
    {
        ($this->onFailedRequestCallback)($index, $this->requests[$index], $exception);
    }

    /**
     * @param \Closure $closure
     */
    public function onCompletedRequest(\Closure $closure)
    {
        $this->onCompletedRequestCallback = $closure;
    }

    /**
     * @param \Closure $closure
     */
    public function onFailedRequest(\Closure $closure)
    {
        $this->onFailedRequestCallback = $closure;
    }
}
