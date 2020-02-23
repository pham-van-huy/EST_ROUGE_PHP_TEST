<?php
include 'helper.php';
include 'define.php';

spl_autoload_register(function ($className) {
    include __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
});

$request = new Request();
$router  = new Router($request);

$router->get('/', 'TodoListController@index');
$router->get('/create', 'TodoListController@create');
$router->post('/store', 'TodoListController@store');
$router->get('/edit', 'TodoListController@edit');
$router->post('/update', 'TodoListController@update');
$router->get('/api-list', 'TodoListController@list');
