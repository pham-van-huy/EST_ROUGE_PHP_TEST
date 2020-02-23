<?php
namespace App\Models;

class TodoList extends Model
{
    const MAX_TITLE       = 50;
    const MAX_DESCRIPTION = 200;
    const STATUS          = ['Planning', 'Doing', 'Complete'];

    public function __construct()
    {
        parent::__construct();
        $this->setFile('TodoList.json');
    }

    public function validate($data)
    {
        $mesErrors = [];

        if (!isset($data['title']) || !strlen($data['title']) || strlen($data['title']) > self::MAX_TITLE) {
            $mesErrors['title'] = 'Length title must from 1 to 50';
        }

        $description = isset($data['description']) ? $data['description'] : '';

        if (!strlen($description) || strlen($description) > self::MAX_DESCRIPTION) {
            $mesErrors['description'] = 'Length description must from 1 to 200';
        }

        $start = new \DateTime($data['start']);
        $end   = new \DateTime($data['end']);

        if ($start >= $end) {
            $mesErrors['start-end'] = 'Start date must be smaller than end date';
        }

        if (!isset($data['status']) || !in_array($data['status'], self::STATUS)) {
            $mesErrors['status'] = 'Status is require';
        }

        return $mesErrors;

    }

}
