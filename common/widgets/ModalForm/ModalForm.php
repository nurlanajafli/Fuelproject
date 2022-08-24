<?php

namespace common\widgets\ModalForm;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ModalForm extends Widget
{
    public $cssClass;
    public $dialogCssClass;
    public $title;
    public $toolbar;
    public $content;
    public $headerCssClass = 'bg-primary text-white';
    public $bodyCssClass = '';
    public $footerCssClass = 'bg-light';
    public $beforeSaveButtonHtml;
    public $closeButtonInHeader = true;
    public $saveButton;
    public $closeButton;
    public $options;
    public $beforeBodyHtml = '';
    public $afterFooterHtml = '';

    public function run()
    {
        $cssClass = 'modal fade';
        if ($this->cssClass) {
            $this->cssClass = "$cssClass {$this->cssClass}";
        } else {
            $this->cssClass = $cssClass;
        }

        $headerCssClass = 'modal-header';
        if ($this->headerCssClass)
            $this->headerCssClass = "$headerCssClass {$this->headerCssClass}";
        else
            $this->headerCssClass = $headerCssClass;

        $bodyCssClass = 'modal-body';
        if ($this->bodyCssClass)
            $this->bodyCssClass = "$bodyCssClass {$this->bodyCssClass}";
        else
            $this->bodyCssClass = $bodyCssClass;

        $dialogCssClass = 'modal-dialog';
        if ($this->dialogCssClass) {
            $this->dialogCssClass = "$dialogCssClass {$this->dialogCssClass}";
        } else {
            $this->dialogCssClass = $dialogCssClass;
        }

        $footerCssClass = 'modal-footer';
        if ($this->footerCssClass) {
            $this->footerCssClass = "$footerCssClass {$this->footerCssClass}";
        } else {
            $this->footerCssClass = $footerCssClass;
        }

        if (is_null($this->saveButton)) {
            $this->saveButton = Yii::t('app', 'Save changes');
        }

        if (is_null($this->closeButton)) {
            $this->closeButton = Yii::t('app', 'Close');
        }

        if ($this->options) {
            if (isset($this->options['dialog'])) {
                $this->dialogCssClass = "$dialogCssClass {$this->options['dialog']['class']}";
            }
            if (isset($this->options['beforeSaveButtonHtml'])) {
                $this->beforeSaveButtonHtml = $this->options['beforeSaveButtonHtml'];
            }
            if (isset($this->options['saveButton'])) {
                $this->saveButton = isset($this->options['saveButton']['html']) ? $this->options['saveButton']['html'] : Yii::t('app', $this->options['saveButton']['caption']);
            }
            if (isset($this->options['closeButton'])) {
                $this->closeButton = Yii::t('app', $this->options['closeButton']['caption']);
            }
        }

        return $this->render('modal');
    }

    public static function registerAjaxReloadCallback($tableSelector, $view, &$formConfig) {
        $callback = uniqid('auto_');
        /** @var \yii\web\View $view */
        $view->registerJs("function $callback(response) {jQuery('$tableSelector').DataTable().ajax.reload();}", \yii\web\View::POS_END);
        $formConfig['options']['data-callback'] = $callback;
    }
}
