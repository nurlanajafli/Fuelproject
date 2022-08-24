<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;
use \yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>
          <div id="userPermissions__message"></div>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="border row">
                <div class="col-sm-4">
                  <?= Grid::widget([
                      'id' => 'users',
                      'dataProvider' => $dataProvider,
                      'columns' => [
                          'id|visible=false',
                          new DataColumn([
                              'title' => Yii::t('app', 'User'),
                              'value' => function ($model) {return $model->email;}
                          ]),
                          'default_office_id|rel=defaultOffice.office|title=Office|filterable=true',
                          new DataColumn([
                              'title' => Yii::t('app', 'Dept'),
                              'value' => function ($model) {
                                  return $model->department ? $model->department->code : '';
                              }
                          ]),
                      ],
                      'info' => false,
                      'dom' => 'Bt',
                      'footCssClass' => 'hide',
                      'colVis' => false,
                      'select' => ['style' => 'single', 'toggleable' => false],
                      'order' => [[1, 'asc']],
                      'attributes' => ['data-url' => Url::toRoute(['permission/index', 'userId' => '{id}'])],
                      'stateSave' => false,
                  ]) ?>
                </div>
                <div class="col-sm-8">
                  <div class="permissions-table">
                    <?= Grid::widget([
                        'id' => 'userPermissions',
                        'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => []]),
                        'columns' => [
                            new DataColumn([
                                'name' => 'category',
                                'title' => Yii::t('app', 'Category'),
                                'value' => function ($model) {
                                    return '';
                                }
                            ]),
                            new DataColumn([
                                'title' => Yii::t('app', 'Description'),
                                'value' => function ($model) {
                                    return '';
                                }
                            ]),
                            'id|visible=false',
                            'can|visible=false',
                        ],
                        'autoWidth' => false,
                        'paging' => false,
                        'template' => '{toolbar}{table}',
                        'colVis' => false,
                        'ordering' => false,
                        'scrollY' => '500px',
                        'scrollCollapse' => true,
                        'rowsGroup' => [
                            'category:name',
                            0
                        ],
                        'stateSave' => false,
                        'info' => false,
                        'select' => ['style' => 'multi'],
                        'buttons' => [
                            ['text' => '<span class="glyphicon glyphicon-star"></span>', 'action' => 'js:function () { this.rows().select(); }'],
                            ['text' => '<span class="glyphicon glyphicon-star-empty"></span>', 'action' => 'js:function () { this.rows().deselect(); }'],
                        ],
                    ]) ?>
                    <?php
                      $model = new \backend\models\PermissionForm();
                      $form = ActiveForm::begin([
                          'action' => Url::toRoute('update'),
                          'enableClientValidation' => false,
                          'options' => ['id' => 'userPermissionsForm', 'class' => 'prevent-submit']
                      ]);
                      echo $form->field($model, 'userId', ['options' => ['class' => 'hide']])->textInput();
                      echo $form->field($model, 'permissions', ['options' => ['class' => 'hide']])->dropDownList(\common\enums\Permission::getUiEnums(), ['multiple' => 'multiple']);
                      echo '<hr><button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> ' . Yii::t('app', 'Save') . '</button>';
                      ActiveForm::end();
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>