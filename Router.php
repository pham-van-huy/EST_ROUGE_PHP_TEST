<?php

class Router
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __call($name, $args)
    {
        list($route, $method) = $args;

        $this->{strtolower($name)}[parse_url($route, PHP_URL_PATH)] = $method;
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    private function requestHandler()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute    = parse_url($this->request->requestUri, PHP_URL_PATH);

        if (array_key_exists($formatedRoute, $methodDictionary)) {

            return $this->callMethod($methodDictionary[$formatedRoute]);
        }

        $this->defaultRequestHandler();
    }

    private function callMethod($method)
    {
        $regex = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*@[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/';

        if (!preg_match($regex, $method)) {
            serverErrorhandler();

            return;
        }

        $arr      = explode('@', $method);
        $class    = 'App\\Controllers\\' . $arr[0];
        $method   = $arr[1];
        $instance = new $class;

        return $instance->{$method}($this->request);

    }

    public function __destruct()
    {
        $this->requestHandler();
    }
}
