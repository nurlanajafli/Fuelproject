<?php


namespace frontend\forms\load;


use yii\base\Model;

class DuplicateLoad extends Model
{
    public $dateReceived;
    public $pickupDate;
    public $copiesToMake;
    public $copyImages;
    public $copyLoadNotes;
    public $copyReferenceNumbers;
    public $postToFreightTracking;

    public function rules() {
        return [
            [['dateReceived', 'pickupDate'], 'required'],
            ['copiesToMake', 'integer', 'min' => 1],
            [['copyImages', 'copyLoadNotes', 'copyReferenceNumbers', 'postToFreightTracking'], 'safe'],
        ];
    }
}