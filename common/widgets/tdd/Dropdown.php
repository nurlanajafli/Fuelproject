<?php

namespace common\widgets\tdd;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class Dropdown extends InputWidget
{
    public $items;
    public $modelClass;
    public $lookupColumnIndex = 0;
    public $displayColumnIndex = 1;
    public $getInitRowJs;
    public $grid;
    public $callback;
    public $resetBtn;
    public $afterHiddenInputHtml = '';

    public $destAttribute;
    public $enableClientScript = false;

    public function run()
    {
        $this->grid['template'] = $this->grid['template'] ?? Yii::$app->params['dt']['templates']['dropdown'];
        if (isset($this->grid['cssClass'])) {
            $this->grid['cssClass'] .= ' js-dd';
        } else {
            $this->grid['cssClass'] = 'js-dd';
        }

        if (!isset($this->grid['stateSave'])) {
            $this->grid['stateSave'] = false;
        }

        if (!isset($this->grid['dataProvider'])) {
            $this->grid['dataProvider'] = new ArrayDataProvider([
                'allModels' => $this->items,
                'modelClass' => $this->modelClass
            ]);
        }
        $this->grid['orderCellsTop'] = true;
        if (!isset($this->grid['foot'])) {
            $this->grid['foot'] = false;
        }

        if (!$this->getInitRowJs) {
            $attributeValue = $this->model->{$this->attribute};
            $this->getInitRowJs = "function (row) { return row[$this->lookupColumnIndex] == '$attributeValue'; }";
        }

        $this->grid['attributes'] = ArrayHelper::merge(
            ['data-form-name' => $this->model->formName(), 'data-dest' => $this->options['id']],
            $this->grid['attributes'] ?? []
        );

        if (is_array($this->destAttribute)) {
            $this->grid['attributes']['data-dest'] = [
                'attributes' => $this->destAttribute[0],
                'i' => $this->destAttribute[1],
            ];
            $t = $id = 0;
            foreach ($this->destAttribute[0] as $attribute => $typeVal) {
                if ($this->model->$attribute) {
                    $t = $typeVal;
                    $id = $this->model->$attribute;
                }
                if ($this->attribute != $attribute) {
                    $hiddenInput = $this->field->form->field($this->model, $attribute, ['template' => '{input}', 'options' => ['tag' => false]])
                        ->hiddenInput(['class' => $this->options['class'] . ' js-tdd-hidden'])->label(false);
                    $this->afterHiddenInputHtml .= $hiddenInput;
                }
            }
            $this->getInitRowJs = "function (row) { return (row[{$this->destAttribute[1]}] == $t) && (row[{$this->destAttribute[2]}] == $id); }";
            $this->lookupColumnIndex = $this->destAttribute[2];
        }

        $this->grid['attributes']['data-lookup'] = $this->lookupColumnIndex;
        $this->grid['attributes']['data-display'] = $this->displayColumnIndex;

        $this->grid['initComplete'] = ($this->grid['initComplete'] ?? '') . '
        let inputId = $(this).data("input");
        let initRow = ' . $this->getInitRowJs . '; 
        $(this).DataTable().rows().every(function(rowIdx, tableLoop, rowLoop) {
        let row = this.data();
        if (initRow(row)) {
            $("#" + inputId).val(row[' . $this->displayColumnIndex . ']);
        }
        });';

        $textInputId = uniqid('auto_');

        if ($this->resetBtn) {
            $this->grid['buttons'] = [
                [
                    'text' => Yii::t('app', 'Reset Value'),
                    'action' => 'js:function (e, dt, node, config) { jQuery("#' . $textInputId . '").val(""); jQuery("#' . $this->options['id'] . '").removeAttr("value").trigger("change"); }',
                    'className' => 'js-reset-btn'
                ]
            ];
            if (is_string($this->resetBtn)) {
                $this->grid['buttons'][0]['text'] = $this->resetBtn;
            }
            if (is_array($this->resetBtn)) {
                $this->grid['buttons'][0] = ArrayHelper::merge($this->grid['buttons'][0], $this->resetBtn);
            }
            $this->grid['dom'] = 'Btp';
        } else {
            $this->grid['dom'] = 'tp';
        }

        $this->options['class'] .= ' js-tdd-hidden';
        $this->grid['colReorder'] = null;
        $this->grid['colVis'] = false;

        if ($this->enableClientScript) Asset::register($this->getView());

        return $this->render('dropdown', ['textInputId' => $textInputId]);
    }
}