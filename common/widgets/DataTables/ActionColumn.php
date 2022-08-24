<?php

namespace common\widgets\DataTables;

class ActionColumn extends Column
{
    public $orderable = false;
    public $searchable = false;
    public $className = 'text-nowrap';
    public $buttons = [];
    public $html;
    public $width = 0;
}
