<?php
/**
 * /var/www/html/backend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
	$actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']);
	$actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud user-index">

    <?php
//         ?>


    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1>
        <?php echo Yii::t('models', 'Users') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">


            <?php /* echo
\yii\bootstrap\ButtonDropdown::widget(
	[
		'id' => 'giiant-relations',
		'encodeLabel' => false,
		'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('app', 'Relations'),
		'dropdown' => [
			'options' => [
				'class' => 'dropdown-menu-right'
			],
			'encodeLabels' => false,
			'items' => [
				[
					'url' => ['accounting-default/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Accounting Default'),
				],
				[
					'url' => ['accounting-default/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Accounting Default'),
				],
				[
					'url' => ['chat-message/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Chat Message'),
				],
				[
					'url' => ['chat-message/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Chat Message'),
				],
				[
					'url' => ['chat-message-seen/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Chat Message Seen'),
				],
				[
					'url' => ['chat-message/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Chat Message'),
				],
				[
					'url' => ['company/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Company'),
				],
				[
					'url' => ['company/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Company'),
				],
				[
					'url' => ['department/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Department'),
				],
				[
					'url' => ['department/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Department'),
				],
				[
					'url' => ['device/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Device'),
				],
				[
					'url' => ['document/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Document'),
				],
				[
					'url' => ['document/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Document'),
				],
				[
					'url' => ['driver/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Driver'),
				],
				[
					'url' => ['driver-adjustment/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Driver Adjustment'),
				],
				[
					'url' => ['driver-adjustment/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Driver Adjustment'),
				],
				[
					'url' => ['driver-compliance/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Driver Compliance'),
				],
				[
					'url' => ['driver-compliance/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Driver Compliance'),
				],
				[
					'url' => ['load/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load'),
				],
				[
					'url' => ['load/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load'),
				],
				[
					'url' => ['load/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load'),
				],
				[
					'url' => ['load-drop/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Drop'),
				],
				[
					'url' => ['load-drop/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Drop'),
				],
				[
					'url' => ['load-movement/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Movement'),
				],
				[
					'url' => ['load-movement/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Movement'),
				],
				[
					'url' => ['load-note/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Note'),
				],
				[
					'url' => ['load-stop/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Stop'),
				],
				[
					'url' => ['load-stop/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Load Stop'),
				],
				[
					'url' => ['payment-term-code/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payment Term Code'),
				],
				[
					'url' => ['payment-term-code/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payment Term Code'),
				],
				[
					'url' => ['payroll-adjustment/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payroll Adjustment'),
				],
				[
					'url' => ['payroll-adjustment/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payroll Adjustment'),
				],
				[
					'url' => ['payroll-batch/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payroll Batch'),
				],
				[
					'url' => ['payroll-batch/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payroll Batch'),
				],
				[
					'url' => ['payroll-pay/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Payroll Pay'),
				],
				[
					'url' => ['setting/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Setting'),
				],
				[
					'url' => ['tracking-log/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Tracking Log'),
				],
				[
					'url' => ['tracking-log/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Tracking Log'),
				],
				[
					'url' => ['truck/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Truck'),
				],
				[
					'url' => ['truck-odometer/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Truck Odometer'),
				],
				[
					'url' => ['truck-odometer/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Truck Odometer'),
				],
				[
					'url' => ['unit/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Unit'),
				],
				[
					'url' => ['unit/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Unit'),
				],
				[
					'url' => ['department/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Department'),
				],

			]
		],
		'options' => [
			'class' => 'btn-default'
		]
	]
); */
?>
        </div>
    </div>

    <hr />

    <div class="table-responsive">
        <?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'pager' => [
			'class' => yii\widgets\LinkPager::className(),
			'firstPageLabel' => Yii::t('app', 'First'),
			'lastPageLabel' => Yii::t('app', 'Last'),
		],
		'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
		'headerRowOptions' => ['class'=>'x'],
		'columns' => [
			// 'auth_key',
			// 'password_hash',
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'email',
                'label' => Yii::t('app', 'User Name'),
                'value' => function ($model) {
                    return $model->username;
                },
            ],
			// 'status',
			// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
			[
				'class' => yii\grid\DataColumn::className(),
				'attribute' => 'department_id',
				'value' => function ($model) {
					if ($rel = $model->department) {
						return Html::a($rel->_label, ['department/view', 'id' => $rel->id, ], ['data-pjax' => 0]);
					} else {
						return '';
					}
				},
				'format' => 'raw',
			],
			// 'password_reset_token',
			// 'verification_token',
			/*'last_name',*/
			/*'first_name',*/
			/*'scope',*/
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'default_office_id',
                'value' => function ($model) {
                    if ($rel = $model->defaultOffice) {
                        return Html::a($rel->_label, ['office/view', 'id' => $rel->id,], ['data-pjax' => 0]);
                    } else {
                        return '';
                    }
                },
                'format' => 'raw'
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'can_access_dispatch_data_from_all_offices',
                'label' => Yii::t('app', 'All Offices'),
                'value' => function ($model) {
                    return $model->can_access_dispatch_data_from_all_offices ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'agent_and_subject_to_agent_rules',
                'label' => Yii::t('app', 'Agent'),
                'value' => function ($model) {
                    return $model->agent_and_subject_to_agent_rules ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'status',
                'label' => Yii::t('app', 'Active'),
                'value' => function ($model) {
                    return $model->status == \common\models\User::STATUS_ACTIVE ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
            ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => $actionColumnTemplateString,
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$options = [
							'title' => Yii::t('app', 'View'),
							'aria-label' => Yii::t('app', 'View'),
							'data-pjax' => '0',
						];
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
					}


				],
				'urlCreator' => function($action, $model, $key, $index) {
					// using the column name as key, not mapping to 'id' like the standard generator
					$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
					$params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
					return Url::toRoute($params);
				},
				'contentOptions' => ['nowrap'=>'nowrap']
			],
		]
	]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>
