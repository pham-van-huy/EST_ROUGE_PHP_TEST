<?php
include 'helper.php';

spl_autoload_register(function ($className) {
    include __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
});

const ROOT_VIEW = __DIR__ . '/App/Views/';
$request        = new Request();

$router = new Router($request);

$router->get('/', 'TodoListController@index');
$router->get('/create', 'TodoListController@create');
$router->post('/store', 'TodoListController@store');
$router->get('/edit', 'TodoListController@edit');
$router->post('/update', 'TodoListController@update');
