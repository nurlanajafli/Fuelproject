<?php
/**
 * @var \yii\web\View $this
 * @var string $textInputId
 */

use yii\helpers\Html;
use common\widgets\DataTables\Grid;
use common\helpers\Utils;

/** @var \common\widgets\tdd\Dropdown $widget */
$widget = $this->context;

if ($widget->callback) {
    if (strpos(ltrim($widget->callback), 'function') === 0) {
        $str = Utils::jsName();
        $this->registerJs(str_replace('function', "function $str", $widget->callback), \yii\web\View::POS_END);
        $widget->grid['attributes']['data-callback'] = $str;
    } else $widget->grid['attributes']['data-callback'] = trim($widget->callback);
}
$widget->grid['attributes']['data-input'] = $textInputId;
echo Html::textInput('', '', ['id' => $textInputId, 'class' => 'form-control dropdown-toggle', 'data-toggle' => 'dropdown', 'readonly' => 'true']);
echo Html::activeHiddenInput($widget->model, $widget->attribute, $widget->options);
echo $widget->afterHiddenInputHtml;
echo Grid::widget($widget->grid);