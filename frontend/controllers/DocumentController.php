<?php

namespace frontend\controllers;

use common\enums\Permission;
use common\models\Document;
use common\models\Load;
use common\widgets\DataTables\Grid;
use frontend\actions\document\UploadAction;
use frontend\controllers\base\BaseController;
use frontend\forms\document\Upload;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class DocumentController extends BaseController
{
    protected $modelClass;

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'data'],
                'permissions' => [Permission::VIEW_DOCUMENT_SCANS]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'data', 'upload'],
                'permissions' => [Permission::ADD_EDIT_DOCUMENT_SCANS]
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($b = parent::beforeAction($action)) {
            if (!ArrayHelper::isIn(Yii::$app->request->get('type'), ['carrier', 'customer', 'driver', 'location', 'load', 'trailer', 'truck', 'vendor'])) {
                throw new NotFoundHttpException();
            }
            $this->modelClass = 'common\\models\\' . ucfirst(strtolower(trim(Yii::$app->request->get('type'))));
        }
        return $b;
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'form' => function ($model) {
                    $form = new Upload();
                    if ($model instanceof Load) {
                        $form->setScenario(Upload::SCENARIO_LOAD);
                    }
                    return $form;
                },
                'viewParams' => function ($model) {
                    return ['id' => $model->id];
                },
                'before' => function ($actionParams) {
                    return call_user_func("$this->modelClass::findOne", $actionParams['id']);
                },
                'save' => function ($form, $model, $button) {
                    /** @var UploadAction $action */
                    $action = $this->action;

                    /** @var Upload $form */
                    $field = $this->actionParams['type'] . '_id';
                    $documentModel = new Document();
                    if (ArrayHelper::isIn($_FILES[$documentModel->formName()]['type'][$documentModel->getImageAttribute()], Yii::$app->params['validDocMimeTypes'])) {
                        $documentModel->setScenario(Document::SCENARIO_INSERT_1);
                    } else {
                        $documentModel->setScenario(Document::SCENARIO_INSERT_2);
                    }
                    $documentModel->{$field} = $model->id;
                    if ($form->getScenario() == Upload::SCENARIO_LOAD) {
                        $documentModel->code = $form->code;
                    } else {
                        $documentModel->description = $form->description;
                    }

                    if ($this->saveModel($documentModel)) {
                        return $action->saveResp(true);
                    }

                    if (!$documentModel->hasErrors($documentModel->getImageAttribute())) {
                        return $action->saveResp(false, [], null, 400);
                    }

                    $form->fakeFile = $documentModel->getFirstError($documentModel->getImageAttribute());
                    return $action->saveResp(true, ['raw' => ActiveForm::validate($form)]);
                }
            ]
        ];
    }

    public function actionIndex($type, $id)
    {
        $model = call_user_func("$this->modelClass::findOne", $id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $this->renderAjax('index', ['model' => $model, 'type' => $type, 'id' => $id]);
    }

    public function actionData($type, $id, $sort = 'asc')
    {
        $model = call_user_func("$this->modelClass::findOne", $id);
        if (!$model) {
            return $this->replyJson(Grid::getEmptyData());
        }

        $document = new Document();
        $grid = new Grid([
            'columns' => [
                'id',
                'code',
                'description|rel=code0.description|coalesce=description',
                $document->getImageAttribute() . '|method=getUrl',
                'created_at|datetime=' . Yii::$app->params['formatter']['datetime']['main'],
                'mime_type'
            ],
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getDocuments()->joinWith('code0')->orderBy(['created_at' => ($sort == 'asc') ? SORT_ASC : SORT_DESC]),
                'pagination' => false
            ])
        ]);
        return $this->replyJson($grid->getData());
    }
}
