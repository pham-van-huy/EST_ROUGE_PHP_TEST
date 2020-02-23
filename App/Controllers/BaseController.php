<?php
namespace App\Controllers;

class BaseController
{
    protected function loadView($path, $data = null)
    {
        include_once ROOT_VIEW . $path;
    }
}
