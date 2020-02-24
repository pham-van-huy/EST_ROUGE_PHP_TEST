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
        // $data = $request->getBody();
        $data = file_get_contents('php://input');
        $data = (array) json_decode($data);

        $mesErrors = $this->todoList->validate($data);

        if (count($mesErrors)) {
            return $this->returnJsonFail($mesErrors, 422);
        } else {
            $data['id'] = uniqid('', true);
            $this->todoList->insertCustome($data);

            return $this->returnJsonSuccess((array) $data);
        }
    }

    public function update($request)
    {
        $data = file_get_contents('php://input');
        $data = (array) json_decode($data);
        $id   = isset($data['id']) ? $data['id'] : null;
        unset($data['id']);
        $mesErrors = $this->todoList->validate($data);

        if (!$id) {
            $this->returnJsonFail(['id' => 'Id is require in request'], 422);

            return;
        }

        if (count($mesErrors)) {
            return $this->returnJsonFail($mesErrors, 422);
        } else {
            $this->todoList->update($data)->where(['id' => $id])->trigger();
            $data['id'] = $id;

            return $this->returnJsonSuccess($data);
        }
    }

    public function getList($request)
    {
        $start  = $request->get('start');
        $end    = $request->get('end');
        $result = [];
        $result = $this->todoList
            ->whereBetweenTime('start', $start)
            ->whereBetweenTime('end', null, $end)
            ->get();

        return $this->returnJsonSuccess(array_values($result));
    }

    public function destroy($request)
    {
        $data = file_get_contents('php://input');
        $data = (array) json_decode($data);
        $id   = isset($data['id']) ? $data['id'] : null;

        if ($id) {
            $this->todoList->delete()->where(['id' => $id])->trigger();

            return $this->returnJsonSuccess(['id' => $id]);
        } else {
            return $this->returnJsonFail(['id' => 'Id is require in request'], 422);

        }
    }
}
