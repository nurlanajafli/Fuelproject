<?php
/**
 * /var/www/html/frontend/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */

use common\models\State;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 *
 * @var View $this
 * @var common\models\Location $model
 * @var yii\widgets\ActiveForm $form
 */

$this->registerCssFile('https://maps-sdk.trimblemaps.com/v2/trimblemaps-2.3.1.css');
$this->registerJsFile('https://maps-sdk.trimblemaps.com/v2/trimblemaps-2.3.1.js', ['position' => View::POS_HEAD]);

$states = State::find()->orderBy(['state_code' => SORT_ASC])->all();
$this->registerJsVar('states', array_map(function ($stateModel) {
    /** @var State $stateModel */
    return ['id' => $stateModel->id, 'stateAbbreviation' => $stateModel->state_code, 'countryAbbreviation' => $stateModel->country_code];
}, $states), View::POS_END);
?>
<section class="edit-form location-form">

    <?php $form = ActiveForm::begin([
		'id' => 'Location',
		'layout' => 'horizontal',
		'enableClientValidation' => true,
		'errorSummaryCssClass' => 'error-summary alert alert-danger',
		'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
	]
);
$this->beginBlock('company');
?>
    <div class="row">
        <div class="col-lg">
            <!-- attribute company_name -->
            <?php echo $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
            <!-- attribute location_name -->
            <?php echo $form->field($model, 'location_name')->textInput(['maxlength' => true]) ?>
            <!-- attribute main_phone -->
            <?php echo $form->field($model, 'main_phone')->textInput(['maxlength' => true]) ?>
            <!-- attribute main_800 -->
            <?php echo $form->field($model, 'main_800')->textInput(['maxlength' => true]) ?>
            <!-- attribute main_fax -->
            <?php echo $form->field($model, 'main_fax')->textInput(['maxlength' => true]) ?>
            <!-- attribute emergency -->
            <?php echo $form->field($model, 'emergency')->textInput(['maxlength' => true]) ?>
            <!-- attribute business_hours -->
            <?php echo $form->field($model, 'business_hours')->textInput(['maxlength' => true]) ?>
            <!-- attribute zone -->
            <?php echo // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
            $form->field($model, 'zone')->dropDownList(
                \yii\helpers\ArrayHelper::map(common\models\Zone::find()->all(), 'code', 'code'),
                [
                    'prompt' => Yii::t('app', 'Select'),
                    'disabled' => (isset($relAttributes) && isset($relAttributes['zone'])),
                ]
            ); ?>
            <!-- attribute office_id -->
            <?php echo // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
            $form->field($model, 'office_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(common\models\Office::find()->all(), 'id', '_label'),
                [
                    'prompt' => Yii::t('app', 'Select'),
                    'disabled' => (isset($relAttributes) && isset($relAttributes['office_id'])),
                ]
            ); ?>
            <!-- attribute address -->
            <div class="form-group row">
                <label class="col-sm-2">Address</label>
                <?php echo $form->field($model, 'address', ['template' => '<div class="col-7">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['maxlength' => true, 'class' => 'form-control pac-target-input'])
                ?>
                <div class="col-1">
                    <button type="button" class="btn js-suggested-address">...</button>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2"><?=Yii::t('app', 'City, State, Zip');?></label>

                <!-- attribute city -->
                <?= $form->field($model, 'city', ['template' => '<div class="col-4">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'City')])
                ?>

                <!-- attribute state_id -->
                <?= // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
                $form->field($model, 'state_id', ['options' => ['tag' => false], 'template' => '<div class="col-2">{input}{error}{hint}</div>'])
                    ->dropDownList(
                        ArrayHelper::map(array_filter($states, function ($stateModel) {
                            /** @var State $stateModel */
                            // TODO: constant value used
                            return $stateModel->country_code == 'US';
                        }), 'id', '_label'),
                        [
                            'prompt' => 'State',
                            'disabled' => (isset($relAttributes) && isset($relAttributes['state_id'])),
                        ]
                    )->label(false); ?>

                <!-- attribute zip -->
                <?= $form->field($model, 'zip', ['template' => '<div class="col-2">{input}{error}{hint}</div>', 'options' => ['tag' => false]])
                    ->textInput(['placeholder' => 'Zip']) ?>

                <?= $form->field($model, 'lat', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput() ?>
                <?= $form->field($model, 'lon', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput() ?>
            </div>
            <!-- attribute time_zone -->
            <?= $form->field($model, 'time_zone', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput() ?>
        </div>
        <div class="col-lg">
			<!-- attribute contact -->
			<?php echo $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>
            <!-- attribute website -->
            <?php echo $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
            <!-- attribute bill_to -->
            <?php echo // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
            $form->field($model, 'bill_to')->widget(\common\widgets\tdd\Dropdown::class, [
                'items' => \common\models\Customer::find()->all(),
                'modelClass' => \common\models\Customer::class,
                'lookupColumnIndex' => 0,
                'displayColumnIndex' => 1,
                'grid' => [
                    'columns' => [
                        new DataColumn([
                            'attribute' => 'id',
                            'visible' => false,
                        ]),
                        'name',
                        'address_1',
                        'city',
                        new DataColumn([
                            'attribute' => 'state_id',
                            'visible' => false,
                        ]),
                        new DataColumn([
                            'attribute' => 'zip',
                            'visible' => false,
                        ]),
                        new DataColumn([
                            'attribute' => 'state_id',
                            'value' => function($model) {
                                /** @var \common\models\Customer $model */
                                if ($rel = $model->state) {
                                    return $rel->_label;
                                }
                                return '';
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Inactive'),
                            'value' => function($model) {
                                /** @var \common\models\Customer $model */
                                return '<input type="checkbox" class="editor-active" checked readonly onclick="return false;">';
                            },
                            'className' => 'dt-body-center text-center',
                            'searchable' => false,
                            'orderable' => false,
                        ]),
                    ]
                ],
                'callback' => 'locationBillToChanged'
            ]); ?>
            <!-- attribute notes -->
            <?php echo $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
            <!-- attribute directions -->
            <?php echo $form->field($model, 'directions')->textarea(['rows' => 6]) ?>
			<!-- attribute appointment_required -->
			<?php echo $form->field($model, 'appointment_required')->checkbox() ?>
			<!-- attribute trailer_pool_location -->
			<?php echo $form->field($model, 'trailer_pool_location')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg">
            <div class="location-map">
                <div class="location-map__panel">
                    <ul class="location-map__list"></ul>
                    <div class="location-map__actions">
                        <a href="#" class="btn btn-primary btn-block js-location-update"><?= Yii::t('app','UPDATE') ?></a>
                    </div>
                    <div class="alert alert-success alert-dismissible mt-3" style="display: none;">
                        <?= Yii::t('app', 'Updated successfully!') ?>
                        <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div id="myMap" style="height: 600px;"></div>
                <script>
                    suggestedAddress = function (params, reverseGeocodeResp) {
                        return $.get("/maps/search?" + params, function (response) {
                            if (reverseGeocodeResp) {
                                $('.location-map__list').empty();
                                $('.location-map__list').append('<li class="location-map__option active js-exact-location"><?= Yii::t('app', 'Exact Location') ?></li>');
                                $('.location-map__list li:last-child').attr('data-response', JSON.stringify({
                                    country_abbreviation: reverseGeocodeResp[0].Address.CountryAbbreviation,
                                    state_abbreviation: reverseGeocodeResp[0].Address.StateAbbreviation,
                                    city: reverseGeocodeResp[0].Address.City,
                                    zip: reverseGeocodeResp[0].Address.Zip,
                                    address: reverseGeocodeResp[0].Address.StreetAddress,
                                    lon: marker.getLngLat().lng,
                                    lat: marker.getLngLat().lat,
                                }));
                                $('.location-map__list').append('<li class="location-map__option-label"><?= Yii::t('app', 'OR SUGGESTED ADDRESS') ?></li>');
                                $(".location-map").css('opacity','1');
                            } else {
                                $('.location-map__option').not('.js-exact-location').remove();
                                $(".location-map").css('opacity','1');
                            }
                            $.each(response, function (index, value) {
                                $('.location-map__list').append('<li class="location-map__option">'+value.address+'</li>');
                                $('.location-map__list li:last-child').attr('data-response', JSON.stringify(value));
                                $(".location-map").css('opacity','1');
                            });
                        });
                    };

                    setMarker = function (d) {
                        myMap.setCenter(d);
                        marker.setLngLat(d);
                        if (!markerAdded) {
                            marker.addTo(myMap);
                            markerAdded = true;
                        }
                    };

                    TrimbleMaps.APIKey = "<?= Yii::$app->pcmiler->apiKey ?>";

                    var myMap = new TrimbleMaps.Map({
                        container: "myMap",
                        <?php if ($model->lon && $model->lat): ?>
                        center: new TrimbleMaps.LngLat(<?= $model->lon ?>, <?= $model->lat ?>),
                        zoom: 14
                        <?php else: ?>
                        center: new TrimbleMaps.LngLat(-96, 35),
                        zoom: 3.3
                        <?php endif; ?>
                    });

                    var marker = new TrimbleMaps.Marker();
                    <?php if ($model->lon && $model->lat): ?>
                    marker.setLngLat([<?= $model->lon ?>, <?= $model->lat ?>]).addTo(myMap);
                    var markerAdded = true;
                    <?php else: ?>
                    var markerAdded = false;
                    <?php endif; ?>

                    myMap.on('click', function (e) {
                        var d = e.lngLat;
                        setMarker(d);
                        var reverseGeocodeLocation = TrimbleMaps.Geocoder.reverseGeocode({
                            lonLat: new TrimbleMaps.LngLat(d.lng, d.lat),
                            region: TrimbleMaps.Common.Region.NA,
                            success: function(response) {
                                // console.log(response);
                                var params = 'query=' + response[0].Address.StreetAddress + '&currentLonLat=' + d.lng + ',' + d.lat;
                                if (response[0].Address.CountryAbbreviation) {
                                    params = params + '&countries=' + response[0].Address.CountryAbbreviation;
                                    if (response[0].Address.StateAbbreviation) {
                                        params = params + '&states=' + response[0].Address.StateAbbreviation;
                                    }
                                }
                                if (typeof saRequest != 'undefined') {
                                    saRequest.abort();
                                }
                                saRequest = suggestedAddress(params, response);
                            },
                            failure: function(response) {
                                // console.log(response);
                            }
                        });
                    });

                    var nav = new TrimbleMaps.NavigationControl({showCompass: false});
                    myMap.addControl(nav, 'top-left');
                </script>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('contacts'); ?>
    <div class="clearfix">
       <button type="button" class="btn btn-sm btn-secondary float-right js-ajax-modal" data-url="<?= Url::toRoute(['location/contact-form', 'location' => $model->id]) ?>">
           <i class="fas fa fa-plus"></i> <?= Yii::t('app', 'Add') ?>
       </button>
    </div>
    <br>
    <?php
    $modelId = $model->id;
    echo Grid::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getLocationContacts(), 'pagination' => false]),
        'columns' => [
            'contact_name',
            new DataColumn([
                'attribute' => 'department_id',
                'value' => function ($model) {
                    if ($rel = $model->department) {
                        return $rel->name;
                    }
                    return '';
                }
            ]),
            'alt_phone_1',
            'email',
            'notes',
            new ActionColumn([
                'html' => function ($model) use ($modelId) {
                    $editUrl = Url::toRoute(['location/contact-form', 'location' => $modelId, 'id' => $model->id]);
                    return Html::a('<i class="fas fa-edit"></i>', '#', ['class' => 'js-ajax-modal px-1', 'data-url' => $editUrl, 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]) .
                        Html::a('<i class="fas fa-trash"></i>', ['contact-delete', 'location' => $modelId, 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
                }
            ])
        ]
    ]); ?>
    <?php $this->endBlock(); ?>
    <?php $activeTab = Yii::$app->request->get('tab', 'company'); ?>
	<?php echo Tabs::widget([
		'encodeLabels' => false,
		'items' => [
			[
				'label' => Yii::t('app', 'Company'),
				'content' => $this->blocks['company'],
				'active' => ($activeTab == 'company'),
			],
			[
			    'label' => Yii::t('app', 'Contacts'),
                'content' => $this->blocks['contacts'],
                'active' => ($activeTab == 'contacts'),
                'disabled' => !$model->id
            ],
		]]); ?>
    <hr/>
	<?php echo $form->errorSummary($model); ?>
	<?php echo Html::submitButton('<i class="fas fa fa-check"></i> '.($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')), ['id' => 'save-' . $model->formName(), 'class' => 'btn btn-success']); ?>
	<?php ActiveForm::end(); ?>
</section>
<?php
$this->beginBlock('updateAddressConfirm');
?>
    <div class="location-confirm-inner">
        <div class="location-confirm-left"><i class="fas fa fa-question-circle fa-3x"></i></div>
        <div class="location-confirm-right">
            <p><?= Yii::t('app','Update Address') ?>:</p>
            <p class="location-confirm-old"> <?= Yii::t('app','old address') ?></p>
            <p><?= Yii::t('app','To') ?>:</p>
            <p class="location-confirm-new"> <?= Yii::t('app','new address') ?></p>
        </div>
    </div>
<?php
$this->endBlock();
$yes = Yii::t('app', 'Yes');
$no = Yii::t('app', 'No');
$this->params['modals'] = [[
    'cssClass' => 'location-confirm-modal',
    'dialogCssClass' => 'modal-dialog-centered',
    'title' => Yii::t('app', 'Express'),
    'content' => $this->blocks['updateAddressConfirm'],
    'footerCssClass' => '',
    'beforeSaveButtonHtml' => '<button class="btn btn-primary js-location-modal-yes" type="button">' . $yes . '</button><button class="btn btn-secondary js-location-modal-no" type="button" data-dismiss="modal">' . $no . '</button>',
    'saveButton' => false,
    'closeButton' => false
]];