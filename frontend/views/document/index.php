<?php
/**
 * @var \yii\web\View $this
 * @var mixed $model
 * @var string $type
 * @var int $id
 */

use common\models\Document;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;

$this->beginBlock('tb');
?>
    <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute(['upload', 'type' => $type, 'id' => $id]) ?>"
            type="button" data-toggle="modal" data-target="#js-file-upload">
        <i class="fas fa fa-upload"></i>
    </button>
    <!--<button class="btn btn-link" type="button" data-toggle="modal" data-target="#js-image-import"
            data-tooltip="Import image"><i class="fas fa fa-download"></i></button>-->
<?php
$this->endBlock();
$this->beginBlock('content');
?>
    <div class="row">
        <div class="col-3">
            <?= Grid::widget([
                'dom' => 'tp',
                'searching' => false,
                'paging' => false,
                'ordering' => false,
                'info' => false,
                'lengthChange' => false,
                'autoWidth' => false,
                'cssClass' => 'js-documents-table',
                'attributes' => ['style' => 'margin:0 !important;'],
                'template' => Yii::$app->params['dt']['templates'][0],
                'colReorder' => null,
                'foot' => false,
                'columns' => [
                    'id|visible=false',
                    'code|visible=false',
                    'description',
                    (new Document())->getImageAttribute() . '|visible=false',
                    'created_at|title=Date'
                ],
                'ajax' => Url::toRoute(['document/data', 'type' => $type, 'id' => $id]),
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'modelClass' => Document::class
                ])
            ]) ?>
        </div>
        <div class="col-9">
        </div>
    </div>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'content' => $this->blocks['content'],
    'cssClass' => 'documents-modal',
    'dialogCssClass' => 'modal-full',
    'saveButton' => false,
    'title' => Yii::t('app', 'Documents'),
    'toolbar' => $this->blocks['tb']
]);
