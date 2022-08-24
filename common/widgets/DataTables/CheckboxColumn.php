<?php

namespace common\widgets\DataTables;

class CheckboxColumn extends Column
{
    public $attribute;
    public $value;

    public function init()
    {
        parent::init();

        $this->value = $this->value ?? function ($model) {
                return true;
            };
    }

    public function html($b)
    {
        return '<input type="checkbox" class="editor-active" onclick="return false;"' . ($b ? ' checked="checked"' : '') . '>';
    }
}