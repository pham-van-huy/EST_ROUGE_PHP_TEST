<?php
namespace App\Controllers;

use App\Models\TodoList;

class TodoListController extends BaseController
{
    protected $todoList;

    public function __construct()
    {
        $this->todoList = new TodoList();
    }

    public function index($request)
    {
        $this->loadView('TodoLists/index.php');
    }

    public function create($request)
    {
        debug('create');
    }

    public function store($request)
    {
        debug('store');
    }

    public function edit($request)
    {
        debug('edit');
    }

    public function update($request)
    {
        debug('update');
    }

    function list($request) {
        $start  = $request->get('start');
        $end    = $request->get('end');
        $result = [];
        $result = $this->todoList
            ->whereBetweenTime('start', $start)
            ->whereBetweenTime('end', null, $end)
            ->get();

        header('Content-Type: application/json');
        echo json_encode(array_values($result));
    }
}
