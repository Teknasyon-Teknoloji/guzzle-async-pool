<?php

namespace {

    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use Psr\Http\Message\RequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Teknasyon\GuzzleAsyncPool\Pool;
    use Teknasyon\GuzzleAsyncPool\Request\GetRequestFactory;
    use Teknasyon\GuzzleAsyncPool\Request\PostRequestFactory;
    use Teknasyon\GuzzleAsyncPool\Request\SoapRequestFactory;
    use Teknasyon\GuzzleAsyncPool\Soap\Decoder;

    include_once realpath(dirname(__DIR__)) . '/vendor/autoload.php';

    $requests = [
        GetRequestFactory::factory(
            'http://127.0.0.1:8080/rest_api.php',
            ['action' => 'Add', 'intA' => 10, 'intB' => 20, 'delay' => 5]
        ),
        GetRequestFactory::factory(
            'http://127.0.0.1:8080/rest_api.php',
            ['action' => 'Subtract', 'intA' => 10, 'intB' => 20]
        ),
        PostRequestFactory::factory(
            'http://127.0.0.1:8080/rest_api.php',
            ['action' => 'Divide', 'intA' => 10, 'intB' => 2]
        ),
        PostRequestFactory::factory(
            'http://127.0.0.1:8080/rest_api.php',
            ['action' => 'Multiply', 'intA' => 10, 'intB' => 2, 'delay' => 3]
        ),
        SoapRequestFactory::factory(
            'http://127.0.0.1:8080/soap_server.php?wsdl',
            'http://127.0.0.1:8080/soap_server.php',
            'http://tempuri.org/Multiply',
            'Multiply',
            ['intA' => 10, 'intB' => 3]
        ),
        SoapRequestFactory::factory(
            'http://127.0.0.1:8080/soap_server.php?wsdl',
            'http://127.0.0.1:8080/soap_server.php',
            'http://tempuri.org/Add',
            'Add',
            ['AddRequest' => ['intA' => 10, 'intB' => 3]]
        )
    ];
    $guzzlePoolSettings = ['concurrency' => 5];
    $guzzleClient = new Client();
    $startTime = microtime(true);
    $pool = new Pool($requests, $guzzlePoolSettings, $guzzleClient);
    $pool->onCompletedRequest(function ($index, RequestInterface $request, ResponseInterface $response) use ($startTime) {
        $body = $response->getBody()->getContents();
        if ($index == 5) {
            $body = json_encode(Decoder::decode($body));
        }
        echo $index . "\t"
            . "OK\t"
            . $request->getMethod() . "\t"
            . $body . "\t"
            . ' Completed in ' . number_format((microtime(true) - $startTime), 2)
            . PHP_EOL;
    });
    $pool->onFailedRequest(function ($index, RequestInterface $request, \Exception $exception) use ($startTime) {
        if ($exception instanceof RequestException) {
            $body = $exception->getResponse()->getBody()->getContents();
            if ($index == 4) {
                try {
                    Decoder::decode($body);
                } catch (SoapFault $fault) {
                    $body = $fault->getMessage();
                }
            }
            echo $index . "\t"
                . "FAIL\t"
                . $request->getMethod() . "\t"
                . $body . "\t"
                . ' Completed in ' . number_format((microtime(true) - $startTime), 2)
                . PHP_EOL;
        } else {
            echo $index . "\t"
                . "FAIL\t"
                . $request->getMethod() . "\t"
                . $exception->getMessage() . "\t"
                . ' Completed in ' . number_format((microtime(true) - $startTime), 2)
                . PHP_EOL;;
        }
    });
    $pool->wait();
    echo 'Completed in ' . number_format((microtime(true) - $startTime), 2) . PHP_EOL;
}
