<?php
namespace App\Controllers;

class BaseController
{
    protected function loadView($path, $data = null)
    {
        include_once ROOT_VIEW . $path;
    }

    protected function returnJsonSuccess($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 200, 'data' => $data]);
    }

    protected function returnJsonFail($error, $status = 405)
    {
        http_response_code(422);
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'error' => $error]);
    }
}
