<?php

namespace {
    class Handler
    {
        public function Add(stdClass $request)
        {
            return ['AddResult' => $request->intA + $request->intB];
        }

        public function Divide(stdClass $request)
        {
            return ['DivideResult' => $request->intA / $request->intB];
        }

        public function Multiply(stdClass $request)
        {
            return ['MultiplyResult' => $request->intA * $request->intB];
        }

        public function Subtract(stdClass $request)
        {
            return ['SubtractResult' => $request->intA - $request->intB];
        }
    }

    if (isset($_GET['wsdl']) || $_SERVER['REQUEST_METHOD'] == 'GET') {
        header('Content-Type: text/xml; charset=utf-8');
        echo file_get_contents(realpath(__DIR__) . '/test.wsdl');
    } else {
        $server = new SoapServer(realpath(__DIR__) . '/test.wsdl');
        $server->setObject(new Handler());
        $server->handle();
    }
}
