<?php

namespace backend\controllers;

use common\enums\LoadRateMethod;
use common\models\LoadRatingDistance;
use common\models\LoadRatingGeograph;
use common\models\LoadRatingMatrix;
use common\models\LoadRatingZipZip;
use common\models\LoadRatingZoneZone;
use common\models\TrailerType;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class LoadRatingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(['query' => LoadRatingMatrix::find()]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new LoadRatingMatrix;

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'number' => $model->number]);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($number)
    {
        $model = $this->findModel($number);

        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $this->getEntitiesProviderByModel($model)
            ]);
        }
    }

    public function actionDelete($number)
    {
        try {
            $this->findModel($number)->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
        }

        return $this->redirect(['index']);
    }

    public function actionCreateRow($number)
    {
        $parentModel = $this->findModel($number);
        $rowModel = $this->getNewRowModel($parentModel);
        $rowModel->matrix = $parentModel->number;
        $rowModel->setScenario($parentModel->rate_type);

        if ($rowModel->load($_POST) && $rowModel->save()) {
            return $this->redirect(['update', 'number' => $number]);
        } else {
            return $this->render('create-row', [
                'parentModel' => $parentModel,
                'rowModel' => $rowModel
            ]);
        }
    }

    public function actionUpdateRow($number, $id)
    {
        $parentModel = $this->findModel($number);
        $rowModel = $this->getRowModel($parentModel, $id);
        $rowModel->setScenario($parentModel->rate_type);

        if ($rowModel->load($_POST) && $rowModel->save()) {
            return $this->redirect(['update', 'number' => $number]);
        } else {
            return $this->render('create-row', [
                'parentModel' => $parentModel,
                'rowModel' => $rowModel
            ]);
        }
    }

    public function actionDeleteRow($number, $id)
    {
        $parentModel = $this->findModel($number);
        $rowModel = $this->getRowModel($parentModel, $id);
        $rowModel->delete();

        return $this->redirect(['update', 'number' => $number]);
    }

    private function getNewRowModel($parentModel)
    {
        switch ($parentModel->rate_method) {
            case LoadRateMethod::ZIP_ZIP :
                $rowModel = new LoadRatingZipZip;
                break;
            case LoadRateMethod::ZONE_ZONE :
                $rowModel = new LoadRatingZoneZone;
                break;
            case LoadRateMethod::GEOGRAPH :
                $rowModel = new LoadRatingGeograph;
                break;
            case LoadRateMethod::DISTANCE :
                $rowModel = new LoadRatingDistance;
                break;
            default:
                throw new NotFoundHttpException();
        }

        return $rowModel;
    }

    private function getRowModel($parentModel, $id)
    {
        switch ($parentModel->rate_method) {
            case LoadRateMethod::ZIP_ZIP :
                $rowModel = LoadRatingZipZip::findOne($id);
                break;
            case LoadRateMethod::ZONE_ZONE :
                $rowModel = LoadRatingZoneZone::findOne($id);
                break;
            case LoadRateMethod::GEOGRAPH :
                $rowModel = LoadRatingGeograph::findOne($id);
                break;
            case LoadRateMethod::DISTANCE :
                $rowModel = LoadRatingDistance::findOne($id);
                break;
            default:
                throw new NotFoundHttpException();
        }

        return $rowModel;
    }


    /**
     * Finds the LoadRatingMatrix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $number
     * @return LoadRatingMatrix the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($number)
    {
        if (($model = LoadRatingMatrix::findOne($number)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    protected function getEntitiesProviderByModel(LoadRatingMatrix $model) {
        $query = null;
        switch ($model->rate_method) {
            case LoadRateMethod::ZIP_ZIP :
                $query = LoadRatingZipZip::find()->where(['matrix' => $model->number]);
                break;
            case LoadRateMethod::ZONE_ZONE :
                $query = LoadRatingZoneZone::find()->where(['matrix' => $model->number]);
                break;
            case LoadRateMethod::GEOGRAPH :
                $query = LoadRatingGeograph::find()->where(['matrix' => $model->number]);
                break;
            case LoadRateMethod::DISTANCE :
                $query = LoadRatingDistance::find()->where(['matrix' => $model->number]);
                break;
            default:
                throw new NotFoundHttpException();
        }

        return new ActiveDataProvider(['query' => $query]);
    }
}
