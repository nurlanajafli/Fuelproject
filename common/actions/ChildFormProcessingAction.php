<?php

namespace common\actions;

class ChildFormProcessingAction extends FormProcessingAction
{
    public function run($parentId = 0, $id = 0)
    {
        return parent::run($id);
    }
}