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
        $todoLists = $this->todoList->get();
        $this->loadView('TodoLists/create.php', $todoLists);
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
}
