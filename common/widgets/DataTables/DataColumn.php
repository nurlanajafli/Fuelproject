<?php

namespace common\widgets\DataTables;

class DataColumn extends Column
{
    /**
     * @var string
     */
    public $attribute;
    /**
     * @var callable
     */
    public $value = null;

    public function __construct($config = [])
    {
        parent::__construct($config);
        if (is_null($this->value)) {
            $this->value = function ($model) {
                $attribute = $this->attribute;
                if (!$attribute) {
                    return null;
                }
                return method_exists($model, 'getAttribute') ? $model->getAttribute($attribute) : $model->$attribute;
            };
        }
    }
}