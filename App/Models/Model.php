<?php
namespace App\Models;

use Libs\Jajo\JSONDB;

class Model extends JSONDB
{
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

    public function whereBetweenTime($col, $start = null, $end = null)
    {
        $start = $start ? new \DateTime($start) : null;
        $end   = $end ? new \DateTime($end) : null;

        if (is_null($start) && is_null($end)) {
            return $this;
        }
        $r = array_filter($this->content, function ($row, $index) use ($col, $start, $end) {
            $row     = (array) $row;
            $timeCol = new \DateTime($row[$col]);

            if (is_null($start)) {
                return $timeCol <= $end;
            }

            if (is_null($end)) {
                return $start <= $timeCol;
            }

            return $start <= $timeCol || $timeCol <= $end;
        }, ARRAY_FILTER_USE_BOTH);

        $this->content = $r;

        return $this;
    }
}
