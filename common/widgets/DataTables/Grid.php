<?php

namespace common\widgets\DataTables;

use common\helpers\Utils;
use common\widgets\Button;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class Grid extends Widget
{
    const BUTTON_SELECT = 0;
    const BUTTON_DESELECT = 1;

    protected $_tags;
    protected $_messageCategory = 'app';

    public $dataProvider;
    public $columns;
    public $cssClass;
    public $toolbarHtml;
    public $rightToolbarHtml;
    public $leftToolbarHtml;
    public $head = true;
    public $foot = true;
    public $footCssClass;
    public $attributes = [];
    public $template;
    public $rowAttributes;
    public $colVis = true;
    public $toolbarLegend;
    public $pageLength = null;
    public $searching = null;
    public $info = null;
    public $lengthChange = null;
    public $autoWidth = false;
    public $paging = null;
    public $ordering = null;
    public $orderCellsTop = null;
    public $order = null;
    public $ajax = null;
    public $dom = 'Bfrtip';
    public $colReorder = ['realtime' => false];
    public $stateSave;
    public $select;
    public $conditionalPaging = true;
    public $initComplete = null;
    public $drawCallback = null;
    public $buttons;
    public $doubleClick;
    public $selectedRows;
    public $scrollY;
    public $scrollCollapse;
    public $rowsGroup;
    public $draw;

    public function init()
    {
        parent::init();
        $this->_tags = [];
        foreach ($this->columns as $k => $v) {
            $tags = '';
            if ($v instanceof Column) {
                $tags = $v->tags;
            } elseif (is_string($v) && ($i = strpos($v, '|'))) {
                $tags = substr($v, $i + 1);
                $this->columns[$k] = substr($v, 0, $i);
            }
            $this->_tags[$k] = ArrayHelper::map(array_map(function ($rawPair) {
                if (count($array = explode('=', $rawPair)) == 1) {
                    $array[] = null;
                } elseif (count($array2 = explode(',', $array[1])) > 1) {
                    $array[1] = $array2;
                }
                if ($array[1] === 'true') {
                    $array[1] = true;
                }
                if ($array[1] === 'false') {
                    $array[1] = false;
                }
                return $array;
            }, explode('|', $tags)), 0, 1);
        }
        if (is_null($this->dataProvider)) {
            $this->dataProvider = new ArrayDataProvider(['allModels' => []]);
        }
    }

    public function getData()
    {
        $result = [];
        $models = $this->dataProvider->getModels();
        foreach ($models as $model) {
            if (is_array($model)) {
                $model = (object)$model;
            }
            $row = [];
            foreach ($this->columns as $k => $column) {
                $value = '';
                switch (true) {
                    case is_string($column):
                        $value = $column === 'null' ? null : $model->$column;
                        break;
                    case $column instanceof DataColumn:
                        $cb = $column->value;
                        $value = $cb($model);
                        break;
                    case $column instanceof CheckboxColumn:
                        if ($str = $column->attribute) {
                            $b = $model->$str;
                        } else {
                            $cb = $column->value;
                            $b = $cb($model);
                        }
                        $value = $column->html($b);
                        break;
                    case $column instanceof ActionColumn:
                        $value = is_callable($column->html) ? call_user_func($column->html, $model) : $column->html;

                        foreach ($column->buttons as $button) {
                            if (isset($button['callback']) && is_callable($cb = $button['callback'])) {
                                $cb($model, $button);
                            }
                            $value .= Button::widget(['options' => $button]);
                        }
                        break;
                    case $column instanceof ActiveColumn:
                        $value = Yii::t('app', 'Active');
                        foreach ($column->attributes ? (array)$column->attributes : [] as $attribute) {
                            if ((is_string($attribute) && $model->$attribute) || (!is_string($attribute) && $attribute($model))) {
                                $value = Yii::t('app', 'Inactive');
                                break;
                            }
                        }
                        break;
                }
                foreach ($this->_tags[$k] ?? [] as $tagName => $tagValue) {
                    switch ($tagName) {
                        case 'rel':
                            $value = '';
                            $chain = array_map(function ($node) {
                                return str_replace(['[', ']'], ['.', ''], $node);
                            }, explode('.', $tagValue));
                            if ($rel = ArrayHelper::getValue($model, array_slice($chain, 0, -1))) {
                                $attribute = end($chain);
                                $value = ($i = strpos($attribute, '(')) ? call_user_func([$rel, substr($attribute, 0, $i)]) : $rel->$attribute;
                            }
                            break;
                        case 'method':
                            $value = call_user_func([$model, $tagValue]);
                            break;
                        case 'filter':
                            $a = (array)($tagValue);
                            $b = array_slice($a, 1);
                            array_unshift($b, $value);
                            $value = call_user_func_array($a[0], $b);
                            break;
                        case 'int':
                        case 'integer':
                            $value = Yii::$app->formatter->asInteger($value);
                            break;
                        case 'decimal':
                            $value = Yii::$app->formatter->asDecimal($value);
                            break;
                        case 'yn':
                            if ($tagValue === 'large') {
                                $value = Yii::t($this->_messageCategory, $value ? 'Yes' : 'No');
                            } else {
                                $value = $value ? 'Y' : 'N';
                            }
                            break;
                        case 'date':
                            if ($value) $value = Yii::$app->formatter->asDate($value, $tagValue);
                            break;
                        case 'datetime':
                            if ($value) $value = Yii::$app->formatter->asDatetime($value, $tagValue);
                            break;
                        case 'time':
                            if ($value) $value = Yii::$app->formatter->asTime(date_create($value), $tagValue);
                            break;
                        case 'coalesce':
                            $value = $value ?: $model->$tagValue;
                            break;
                        case 'default':
                            $value = $value ?: Yii::t('app', $tagValue);
                            break;
                        case 'abbreviation':
                            $value = Utils::abbreviation($value, $tagValue);
                            break;
                        case 'multiply':
                            $value *= $tagValue;
                            break;
                    }
                }
                $row[] = $value;
            }
            $result[] = $row;
        }
        return ['data' => $result];
    }

    public static function getEmptyData()
    {
        return ['data' => []];
    }

    public function run()
    {
        $columns = [];
        $filterableColumns = [];
        $model = null;
        if (($this->dataProvider instanceof ActiveDataProvider) && ($this->dataProvider->query instanceof ActiveQuery) && $this->dataProvider->query->modelClass) {
            $class = $this->dataProvider->query->modelClass;
            $model = new $class();
        } elseif ($this->dataProvider instanceof ArrayDataProvider) {
            $class = $this->dataProvider->modelClass;
            if ($class)
                $model = new $class();
        }

        $headerJs = '';
        foreach ($this->columns as $k => $column) {
            $value = [];
            if (is_string($column) && !ArrayHelper::getValue($this->_tags, [$k, 'title'])) {
                $value['title'] = $model ?
                    $model->getAttributeLabel($column) : ucwords(strtolower(str_replace('_', ' ', $column)));
            }

            $filterable = false;
            if ($column instanceof Column) {
                $value['title'] = $column->title;
                if ($column->width) {
                    $value['width'] = $column->width;
                }
                if ($column->orderable !== null) {
                    $value['orderable'] = $column->orderable;
                }
                if ($column->searchable !== null) {
                    $value['searchable'] = $column->searchable;
                }
                if ($column->visible !== null) {
                    $value['visible'] = $column->visible;
                }
                if ($column->className) {
                    $value['className'] = $column->className;
                }
                if ($column->name) {
                    $value['name'] = $column->name;
                }
                if ($column->defaultContent) {
                    $value['defaultContent'] = $column->defaultContent;
                }
                $filterable = $column->filterable;
            }

            if ((($column instanceof DataColumn) || ($column instanceof CheckboxColumn)) && !$column->title && $column->attribute) {
                $value['title'] = $model->getAttributeLabel($column->attribute);
            }

            if ($column instanceof ActiveColumn) {
                $value['title'] = Yii::t('app', 'Active');
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'filterable'])) !== null) {
                $filterable = $tagValue;
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'orderable'])) !== null) {
                $value['orderable'] = $tagValue;
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'searchable'])) !== null) {
                $value['searchable'] = $tagValue;
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'visible'])) !== null) {
                $value['visible'] = $tagValue;
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'title']))) {
                $value['title'] = $tagValue;
            }

            if (($tagValue = ArrayHelper::getValue($this->_tags, [$k, 'className']))) {
                $value['className'] = $tagValue;
            }

            if ($filterable) {
                $filterableColumns[] = $k;
            }

            if (is_array($column)) {
                $value = $column;
            }

            if (!isset($value['title']) || is_null($value['title'])) $value['title'] = '';

            $columns[] = $value;
        }

        if (!is_array($this->buttons)) $this->buttons = [];
        $this->buttons = array_map(function ($gridButton) {
            if ($gridButton === static::BUTTON_SELECT) {
                return ['text' => '<i class="fas fa-star"></i>', 'action' => 'js:dtSelectAll'];
            }
            if ($gridButton === static::BUTTON_DESELECT) {
                return ['text' => '<i class="far fa-star"></i>', 'action' => 'js:dtDeselectAll'];
            }
            return $gridButton;
        }, $this->buttons);

        if ($this->colVis)
            $this->buttons[] = [
                'extend' => 'colvis',
                'columns' => ':gt(0)',
                'className' => 'btn-primary',
                'text' => Yii::t('app', 'Hide/Show Columns'),
                'init' => 'js:function (api, node, config) {$(node).removeClass("btn-secondary");}'
            ];

        if (is_null($this->rowAttributes)) $this->rowAttributes = []; else $this->rowAttributes = (array)$this->rowAttributes;

        $initComplete = $headerJs . $this->initComplete;
        if ($this->selectedRows) {
            $this->rowAttributes[] = 'function (data) {let ids = [' . implode(',', $this->selectedRows) .
                ']; return ids.includes(data[0]) ? "js-tbs" : ""; }';
            $initComplete .= '$("#" + settings.sInstance).DataTable().rows(".js-tbs").select(); $("#" + settings.sInstance + " tr.js-tbs").removeClass("js-tbs");';
        }

        $this->initComplete = "js:function (settings) {{$initComplete}}";
        if ($this->rowAttributes || $this->doubleClick) {
            $this->drawCallback = 'js:function (settings) {
            let t = $(this); t.DataTable().rows({page: "current"}).every(function(rowIdx, tableLoop, rowLoop) {
            let rowData = this.data(); let r = $("tbody tr:nth-child(" + (rowLoop + 1) + ")", t);';
            foreach ($this->rowAttributes as $i => $rowAttribute) {
                $this->drawCallback .= '
                let f' . $i . ' = ' . (is_int($rowAttribute) ? "function (rowData) { return rowData[$rowAttribute]; }" : $rowAttribute) . '; 
                let result' . $i . ' = f' . $i . '(rowData);
                if (result' . $i . ') {
                    if (typeof result' . $i . ' == "string") {
                        if (result' . $i . ') {
                            r.addClass(result' . $i . ');
                        }
                    } else {
                        Object.entries(result' . $i . ').forEach(element => r.attr(element[0], element[1]));
                    }
                }';
            }
            if ($this->doubleClick && $this->doubleClick[0] == 'modal') {
                $this->drawCallback .= ' 
                let url = "' . $this->doubleClick[1] . '"; 
                $.each(url.match(/col%3A([0-9]+)/g), function (_index, value) {
                    value = value.substring(6);
                    url = url.replace("col%3A" + value, encodeURI(rowData[value]));
                });
                r.data("modal-url", url);';
            }
            $this->drawCallback .= '});}';
        }

        if (is_null($this->stateSave) && $this->id) {
            $this->stateSave = true;
        }

        return $this->render('grid', [
            'columns' => $columns,
            'filterableColumns' => $filterableColumns
        ]);
    }

    /**
     * @return string
     */
    public function getMessageCategory(): string
    {
        return $this->_messageCategory;
    }
}
