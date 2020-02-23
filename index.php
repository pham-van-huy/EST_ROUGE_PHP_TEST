<?php
include 'helper.php';
include 'define.php';

spl_autoload_register(function ($className) {
    include __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
});

$request = new Request();
$router  = new Router($request);

$router->get('/', 'TodoListController@index');
$router->post('/api-update', 'TodoListController@update');
$router->post('/api-destroy', 'TodoListController@destroy');
$router->get('/api-list', 'TodoListController@getList');
$router->post('/api-create', 'TodoListController@create');
