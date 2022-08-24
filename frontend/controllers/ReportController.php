<?php

namespace frontend\controllers;

use common\enums\Permission;
use common\models\Document;
use common\models\Load;
use common\models\Report;
use frontend\controllers\base\BaseController;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\web\HttpException;

class ReportController extends BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'permissions' => [Permission::VIEW_REPORTS]
            ],
        ];
    }

    public function allowedAttributes()
    {
        return [
            'loads' => ['ids']
        ];
    }

    public function requiredAttributes()
    {
        return [
            'loads' => ['ids']
        ];
    }

    public function actionLoads()
    {
        $post = $this->getAllowedPost();
        $array = array_filter(is_array($post['ids']) ? $post['ids'] : [], function ($id) {
            return is_integer($id);
        });
        /** @var Load[] $loads */
        $loads = Load::find()
            ->andWhere(['id' => $array, 'created_by' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();
        foreach ($loads as $load) {
            $content = '';
            /** @var Document[] $documents */
            $documents = $load->getDocuments()->orderBy(['created_at' => SORT_ASC])->all();
            foreach ($documents as $document) {
                $imgHtml = Html::img($document->getUploadUrl($document->getImageAttribute()), []);
            }
            $pdf = new Pdf(ArrayHelper::merge(Yii::$app->params['Pdf'], [
                'destination' => Pdf::DEST_FILE,
                'filename' => '/tmp/cfam' . $load->id . '.pdf',
                'content' => $content
            ]));
            $pdf->render();
        }
    }

    public function actionIndex() {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Report::find(),
            'pagination' => false
        ]);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
