<?php

namespace frontend\forms\truck;

use yii\base\Model;

class OutOfService extends Model
{
    const REMOVE_FROM_UNIT = 'remove_from_unit';
    const MAKE_INACTIVE = 'make_inactive';
    const KEEP_WITH_UNIT = 'keep_with_unit';

    public $truck;
    public $drivers;
    public $trailers;

    public function rules()
    {
        return [
            [['truck', 'drivers', 'trailers'], 'required'],
            [['truck', 'drivers', 'trailers'], 'string'],
            [['truck'], 'in', 'range' => [self::REMOVE_FROM_UNIT, self::MAKE_INACTIVE]],
            [['drivers'], 'in', 'range' => [self::REMOVE_FROM_UNIT, self::KEEP_WITH_UNIT]],
            [['trailers'], 'in', 'range' => [self::REMOVE_FROM_UNIT, self::MAKE_INACTIVE]],
        ];
    }
}