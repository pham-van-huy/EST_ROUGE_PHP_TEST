<?php
namespace App\Controllers;

class BaseController
{
    protected function loadView($path, $data)
    {
        include_once ROOT_VIEW . $path;
    }
}
