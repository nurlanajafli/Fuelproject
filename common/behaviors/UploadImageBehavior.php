<?php

namespace common\behaviors;

use mohorev\file\UploadImageBehavior as BaseUploadImageBehavior;
use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class UploadImageBehavior extends BaseUploadImageBehavior
{
    public $fileInfoAttributes = [];

    protected $thumbsClosure;

    public function init()
    {
        parent::init();

        if ($this->generateNewName === true) {
            $this->generateNewName = function ($file) {
                return Yii::$app->security->generateRandomString(50) . '.' . $file->extension;
            };
        }

        if (is_callable($this->thumbs)) {
            $this->thumbsClosure = $this->thumbs;
            $this->thumbs = call_user_func($this->thumbsClosure);
        }
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (ArrayHelper::isIn($model->getScenario(), $this->scenarios) && ($this->file instanceof UploadedFile)) {
            if (is_callable($this->thumbsClosure)) {
                $this->thumbs = call_user_func($this->thumbsClosure);
            }
        }
    }

    public function beforeSave()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (ArrayHelper::isIn($model->getScenario(), $this->scenarios) && ($this->file instanceof UploadedFile)) {
            if ($name = ArrayHelper::getValue($this->fileInfoAttributes, 'mimeType')) {
                $model->setAttribute($name, $this->file->type);
            }
            $image = Image::getImagine()->open($this->file->tempName);
            $imageSize = $image->getSize();
            if ($name = ArrayHelper::getValue($this->fileInfoAttributes, 'width')) {
                $model->setAttribute($name, $imageSize->getWidth());
            }
            if ($name = ArrayHelper::getValue($this->fileInfoAttributes, 'height')) {
                $model->setAttribute($name, $imageSize->getHeight());
            }
            if ($name = ArrayHelper::getValue($this->fileInfoAttributes, 'size')) {
                $model->setAttribute($name, $this->file->size);
            }
        }
        parent::beforeSave();
    }
}
