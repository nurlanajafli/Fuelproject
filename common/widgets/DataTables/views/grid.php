<?php
/**
 * @var \yii\web\View $this
 * @var array $columns
 * @var array $filterableColumns
 */

/** @var \common\widgets\DataTables\Grid $widget */
$widget = $this->context;
$widget->attributes['data-var'] = uniqid('tConf_');
$options = is_null($widget->ajax) ? $widget->getData() : [];
$options['columns'] = $columns;

foreach (
    [
        'pageLength',
        'searching',
        'info',
        'ajax',
        'lengthChange',
        'autoWidth',
        'paging',
        'ordering',
        'orderCellsTop',
        'order',
        'initComplete',
        'drawCallback',
        'dom',
        'buttons',
        'colReorder',
        'stateSave',
        'select',
        'conditionalPaging',
        'scrollY',
        'scrollCollapse',
        'rowsGroup',
        'draw'
    ] as $option) {
    if (!is_null($widget->$option)) {
        $options[$option] = $widget->$option;
    }
}
$this->beginBlock('toolbar');
if ($filterableColumns || $widget->toolbarHtml || $widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend) {
    if ($widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend)
        echo '<div class="dt-toolbar justify-content-between">';
    else
        echo '<div class="dt-toolbar">';

    if ($widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend) echo '<div class="dt-toolbar__left">';
    if ($widget->toolbarHtml) {
        echo '<div class="dt-toolbar-actions">' . $widget->toolbarHtml . '</div>';
    }
    if ($widget->leftToolbarHtml) {
        echo '<div class="dt-toolbar-actions">' . $widget->leftToolbarHtml . '</div>';
    }
    if ($filterableColumns) {
        echo '<div class="dt-toolbar-filter js-table-filter">';
        array_walk($filterableColumns, function ($index, $key) {
            echo '<div class="dt-toolbar-filter-item"><select class="custom-select" data-column-index="' . $index . '"></select></div>';
        });
        echo '</div>';
    }
    if ($widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend) echo "</div>";
    if ($widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend) echo '<div class="dt-toolbar__right">';
    if ($widget->toolbarLegend) {
        echo '<ul class="dt-toolbar-legend">';
        foreach ($widget->toolbarLegend as $key => $value) {
            echo '<li class="dt-legend-' . $key . '" data-toggle="tooltip" data-placement="top" title="' . \yii\helpers\Html::encode($value) . '"></li>';
        }
        echo '</ul>';
    }
    if ($widget->rightToolbarHtml) {
        echo strpos($widget->rightToolbarHtml, 'dt-toolbar-actions') ? $widget->rightToolbarHtml : '<div class="dt-toolbar-actions">' . $widget->rightToolbarHtml . '</div>';
    }
    if ($widget->leftToolbarHtml || $widget->rightToolbarHtml || $widget->toolbarLegend) echo "</div>";
    echo '</div>';
}
$this->endBlock();
$this->beginBlock('table');
echo sprintf('<script>let %s=%s;</script>', $widget->attributes['data-var'], common\helpers\JsonHelper::encode($options));
$str = '';
foreach ($columns as $column) {
    $str .= "<th>{$column['title']}</th>";
}
$tContent = "<thead" . ($widget->head ? '' : ' style="display:none;"') . "><tr>$str</tr></thead>";
if ($widget->foot) {
    $tContent .= "<tfoot" . ($widget->footCssClass ? ' class="' . $widget->footCssClass . '"' : '') . "><tr>$str</tr></tfoot>";
}
echo \yii\helpers\Html::tag('table', $tContent, \yii\helpers\ArrayHelper::merge([
    'class' => 'table table-bordered compact js-datatable' . ($widget->cssClass ? " {$widget->cssClass}" : ''),
    'id' => $id = $widget->id ?: uniqid('dt-')
], $widget->attributes));
$this->endBlock();
echo str_replace(
    ['{toolbar}', '{table}'],
    [$this->blocks['toolbar'], $this->blocks['table']],
    $widget->template ?: Yii::$app->params['dt']['templates']['default']
);
