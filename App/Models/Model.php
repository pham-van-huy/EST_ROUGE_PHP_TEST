<?php
namespace App\Models;

use Libs\Jajo\JSONDB;

class Model extends JSONDB
{
    const STATUS_PLANNING = 1;
    const STATUS_DOING    = 2;
    const STATUS_COMPLETE = 3;
    public $fileJson;

    public function __construct()
    {
        parent::__construct(__DIR__ . '/Databases');
    }
    protected function setFile($file)
    {
        $this->fileJson = $file;
        $this->from($this->fileJson);
    }

    public function insertCustome($values)
    {
        return parent::insert($this->fileJson, $values);
    }
}
