<?php
namespace App\Models;

class TodoList extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setFile('TodoList.json');
    }
}
