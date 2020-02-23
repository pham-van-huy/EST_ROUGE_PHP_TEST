<?php
namespace App\Models;

class TodoList extends Model
{
    const STATUS_PLANNING = 'Planning';
    const STATUS_DOING    = 'Doing';
    const STATUS_COMPLETE = 'Complete';

    public function __construct()
    {
        parent::__construct();
        $this->setFile('TodoList.json');
    }
}
