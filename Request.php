<?php

class Request
{

    public function __construct()
    {
        $this->initProperty();
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_GET;
        }

        return $_GET[$key] ?? $default;
    }

    public function getBody($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_POST;
        }

        return $_POST[$key] ?? $default;
    }

    private function initProperty()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c      = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

}
