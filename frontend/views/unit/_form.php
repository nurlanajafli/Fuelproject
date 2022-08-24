<?php
/**
 * /var/www/html/frontend/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 * @var common\models\Unit $model
 *
 */

use common\models\Driver;
use common\models\Office;
use common\models\Trailer;
use common\models\Truck;
use common\models\Unit;
use common\widgets\DataTables\DataColumn;
use common\widgets\tdd\Dropdown;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$options = [];
if (!$model->id) {
    $options['data-set-location'] = Url::toRoute('set-location');
    $options['data-cb'] = 'unitsetlctn';
}

$relAttributes = $model->attributes;
?>
<section class="edit-form unit-form">
<?php
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'id' => 'Unit',
    'layout' => 'horizontal',
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
    'fieldConfig' => ArrayHelper::merge(Yii::$app->params['activeForm']['horizontalFormConfig'], ['options' => ['class' => 'field row']]),
    'options' => $options
]));
$this->beginBlock('main');
?>
    <div class="row">
        <div class="col-6">
            <div class="form-fieldset">
                <span class="form-legend"><?= Yii::t('app', 'Assignments') ?></span>
                <?= $form->field($model, 'active')->textInput(['type' => 'date']) ?>
                <?= $form->field($model, 'driver_id')->widget(Dropdown::class, $widgetConfig = [
                    'items' => Driver::find()->all(),
                    'modelClass' => Driver::class,
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 2,
                    'grid' => [
                        'columns' => [
                            new DataColumn([
                                'title' => Yii::t('app', 'id'),
                                'attribute' => 'id',
                                'visible' => false
                            ]),
                            new DataColumn([
                                'title' => Yii::t('app', 'cell_phone'),
                                'attribute' => 'cell_phone',
                                'visible' => false
                            ]),
                            new DataColumn([
                                'title' => Yii::t('app', 'Name'),
                                'value' => function ($model) {
                                    /** @var Driver $model */
                                    return $model->_label;
                                }
                            ]),
                            new DataColumn([
                                'title' => Yii::t('app', 'Date'),
                                'value' => function ($model) {
                                    /** @var Driver $model */
                                    return $model->hire_date;
                                }
                            ]),
                            'city',
                            new DataColumn([
                                'title' => Yii::t('app', 'St'),
                                'value' => function ($model) {
                                    /** @var Driver $model */
                                    if ($rel = $model->state) {
                                        return $rel->state_code;
                                    }
                                    return '';
                                }
                            ]),
                            'status',
//                            new DataColumn([
//                                'title' => Yii::t('app', 'Trip'),
//                                'value' => function ($model) {
//                                    /** @var \common\models\Driver $model */
//                                    // TODO: temp
//                                    return 'TL';
//                                }
//                            ]),
                        ],
                        'order' => [[2, 'asc']]
                    ],
                    'callback' => 'function (row) {jQuery("#driver_cell_phone").text(row[1]); return [];}'
                ]) ?>
                <div class="row">
                    <div class="col-4 offset-2">
                        <div class="field">
                            <p class="form-readonly-text" id="driver_cell_phone"><?= $model->driver ? $model->driver->cell_phone : '&nbsp;' ?></p>
                        </div>
                    </div>
                </div>
                <?php
                $widgetConfig['callback'] = 'function (row) {jQuery("#co_driver_cell_phone").text(row[1]); return [];}';
                echo $form->field($model, 'co_driver_id')->widget(Dropdown::class, $widgetConfig)
                ?>
                <div class="row">
                    <div class="col-4 offset-2">
                        <div class="field">
                            <p class="form-readonly-text" id="co_driver_cell_phone"><?= $model->coDriver ? $model->coDriver->cell_phone : '&nbsp;' ?></p>
                        </div>
                    </div>
                </div>
                <?= $form->field($model, 'truck_id')->widget(Dropdown::class, [
                    'items' => Truck::find()->where(['not in','id',$allSelectedParts['allSelectedTrucks']])->all(),
                    'modelClass' => Truck::class,
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 1,
                    'grid' => [
                        'columns' => [
                            new DataColumn([
                                'attribute' => 'id',
                                'visible' => false,
                            ]),
                            'truck_no',
                            'type',
                            'make',
                            'model',
                            'status',
//                            new DataColumn([
//                                'title' => Yii::t('app', 'Date'),
//                                'value' => function ($model) {
//                                    /** @var Truck $model */
//                                    // TODO: temp
//                                    return '1/25/21';
//                                }
//                            ]),
//                            new DataColumn([
//                                'title' => Yii::t('app', 'City'),
//                                'value' => function ($model) {
//                                    /** @var Truck $model */
//                                    // TODO: temp
//                                    return 'Phoenix';
//                                }
//                            ]),
//                            new DataColumn([
//                                'title' => Yii::t('app', 'St'),
//                                'value' => function ($model) {
//                                    /** @var Truck $model */
//                                    // TODO: temp
//                                    return 'AZ';
//                                }
//                            ]),
//                            new DataColumn([
//                                'title' => Yii::t('app', 'Trip'),
//                                'value' => function ($model) {
//                                    /** @var Truck $model */
//                                    // TODO: temp
//                                    return 'TL';
//                                }
//                            ]),
                        ],
                        'order' => [[1, 'asc']]
                    ]
                ]) ?>
                <?= $form->field($model, 'trailer_id')->widget(Dropdown::class, $widgetConfig = [
                    'items' => Trailer::find()->where(['not in','id',$allSelectedParts['allSelectedTrailers']])->all(),
                    'modelClass' => Trailer::class,
                    'lookupColumnIndex' => 0,
                    'displayColumnIndex' => 1,
                    'grid' => [
                        'columns' => [
                            new DataColumn([
                                'attribute' => 'id',
                                'visible' => false,
                            ]),
                            'trailer_no',
                            'type',
                            'make',
                            'model',
//                            'length',
//                            'height',
//                            new DataColumn([
//                                'title' => Yii::t('app', 'Date'),
//                                'value' => function ($model) {
//                                    /** @var Trailer $model */
//                                    // TODO: temp
//                                    return '5/15/19';
//                                }
//                            ]),
//                            new DataColumn([
//                                'title' => Yii::t('app', 'City'),
//                                'value' => function ($model) {
//                                    /** @var Trailer $model */
//                                    // TODO: temp
//                                    return 'Greencastle';
//                                }
//                            ]),
//                            new DataColumn([
//                                'title' => Yii::t('app', 'St'),
//                                'value' => function ($model) {
//                                    /** @var Trailer $model */
//                                    // TODO: temp
//                                    return 'PA';
//                                }
//                            ]),
                            'status',
//                            new DataColumn([
//                                'title' => Yii::t('app', 'Trip'),
//                                'value' => function ($model) {
//                                    /** @var Trailer $model */
//                                    // TODO: temp
//                                    return 'TL';
//                                }
//                            ]),
                        ],
                        'order' => [[1, 'asc']]
                    ],
                    'resetBtn' => Yii::t('app', 'Reset Value')
                ]) ?>
                <?= $form->field($model, 'trailer_2_id')->widget(Dropdown::class, $widgetConfig) ?>
                <?= $form->field($model, 'office_id')->dropDownList(
                    ArrayHelper::map(Office::find()->all(), 'id', '_label'), ['prompt' => Yii::t('app', 'Select')]
                ) ?>
            </div>
        </div>
        <div class="col-6">
            <div class="form-fieldset">
                <span class="form-legend"><?= $model->getAttributeLabel('notes') ?></span>
                <?= $form->field($model, 'notes', ['template' => '{input}{error}'])->textarea(['rows' => 15]) ?>
            </div>
        </div>
    </div>
    <?php
    // TODO: static
    ?>
    <div class="row">
        <div class="col-12">
            <div class="form-fieldset">
                <span class="form-legend"><?=Yii::t('app', 'Status')?></span>
                <div class="row">
                    <div class="col-4">
                        <div class="field row">
                            <label class="col-sm-2"><?=Yii::t('app', 'Status')?></label>
                            <p class="form-readonly-text"><?= $model->status ?></p>
                        </div>
                        <div class="field row">
                            <label class="col-sm-2"><?=Yii::t('app', 'Date')?></label>
                            <p class="form-readonly-text"><?= Yii::$app->formatter->asDate($model->active) ?></p>
                        </div>
                        <div class="field row">
                            <label class="col-sm-2"><?=Yii::t('app', 'Trip Type')?></label>
                            <p class="form-readonly-text"><?=Yii::t('app', 'TL')?></p>
                        </div>
                        <div class="field row">
                            <label class="col-sm-2"><?=Yii::t('app', 'Load No')?></label>
                            <p class="form-readonly-text"></p>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="field row">
                            <label>Location</label>
                            <p class="form-readonly-text">
                                <?php
                                    $lastLocation = \common\models\TrackingLog::getLatestUnitLocation($model->id);
                                    if ($lastLocation)
                                        echo implode(", ", [$lastLocation->address, $lastLocation->city, $lastLocation->state->state_code, $lastLocation->zip]);
                                    else
                                        echo 'N/A';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
	<?= Tabs::widget([
		'encodeLabels' => false,
		'items' => [
			[
				'label' => Yii::t('app', 'Unit Info'),
				'content' => $this->blocks['main'],
				'active' => true,
			],
	    ]
    ]) ?>
    <hr/>
	<?php
    echo $form->errorSummary($model);
    if (!$model->id) {
        $btnContent = '<i class="fas fa fa-check"></i> ' . Yii::t('app', 'Create');
        $btnOptions = ['class' => 'btn btn-success js-submit', 'data-id' => 'save'];
        echo Html::button($btnContent, $btnOptions);
    } else {
        $btnContent = '<i class="fas fa fa-check"></i> ' . Yii::t('app', 'Save');
        $btnOptions = ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success'];
        echo Html::submitButton($btnContent, $btnOptions);
    }
    ActiveForm::end();
    ?>
</section>
