<?php

namespace common\widgets\DataTables;

use yii\base\BaseObject;

class Column extends BaseObject
{
    public $title;
    public $width;
    public $orderable = true;
    public $searchable = true;
    public $visible = true;
    public $className = '';
    public $filterable = false;
    public $tags = '';
    public $name;
    public $defaultContent;
}