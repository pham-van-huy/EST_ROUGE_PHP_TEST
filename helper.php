<?php

function debug(...$variables)
{
    foreach ($variables as $key => $variable) {
        echo '<pre>';
        print_r($variable);
        echo '</pre>';
        echo '<hr />';
    }
}

function serverErrorhandler()
{
    echo "Server Error!";
}

function obj_to_array($obj)
{
    // Not an array or object? Return back what was given
    if (!is_array($obj) && !is_object($obj)) {
        return $obj;
    }

    $arr = (array) $obj;

    foreach ($arr as $key => $value) {
        $arr[$key] = obj_to_array($value);
    }

    return $arr;
}
