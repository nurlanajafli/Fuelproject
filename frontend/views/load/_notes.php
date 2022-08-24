<?php
/**
 * @var \common\models\Load $model
*/

use common\enums\I18nCategory;

?>
<div class="row">
    <div class="col">
        <div class="form-fieldset">
            <div class="row">
                <div class="col">
                    <button class="btn btn-light"><i class="fas fa-calculator"></i></button>
                    <button class="btn btn-light js-ajax-modal" data-url="<?= \yii\helpers\Url::toRoute(['add-note', 'id' => $model->id]) ?>"><i class="fas fa-edit"></i></button>
                </div>
                <div class="col-11">
                    <?= \common\widgets\DataTables\Grid::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'modelClass' => \common\models\LoadNote::class
                        ]),
                        'id' => 'automatic-note-list',
                        'ajax' => \yii\helpers\Url::toRoute(['load/notes', 'id' => $model->id]),
                        'dom' => 't',
                        'paging' => false,
                        'columns' => [
                            new \common\widgets\DataTables\DataColumn([
                                'title' => Yii::t('app', 'Posted By'),
                                'orderable' => false,
                                'searchable' => false
                            ]),
                            new \common\widgets\DataTables\DataColumn([
                                'title' => Yii::t('app', 'Type'),
                                'orderable' => false,
                                'searchable' => false
                            ]),
                            new \common\widgets\DataTables\DataColumn([
                                'title' => Yii::t('app', 'Date'),
                                'orderable' => false,
                                'searchable' => false
                            ]),
                            new \common\widgets\DataTables\DataColumn([
                                'title' => Yii::t('app', 'Time'),
                                'orderable' => false,
                                'searchable' => false
                            ]),
                            new \common\widgets\DataTables\DataColumn([
                                'title' => Yii::t('app', 'LA'),
                                'orderable' => false,
                                'searchable' => false
                            ]),
                            new \common\widgets\DataTables\DataColumn([
                                'attribute' => 'notes',
                                'orderable' => false,
                                'searchable' => false
                            ]),
                        ],
                        'template' => '{table}',
                        'cssClass' => 'js-load-notes-table d-block'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
