<?php

namespace garage\controllers;

use common\enums\ReportStatus;
use common\helpers\Hosts;
use common\models\Report;
use common\models\ReportFlag;
use common\models\ReportMedia;
use garage\components\HttpException;
use garage\forms\ReportCreate;
use garage\forms\ReportSign;
use garage\templates\report\Large;
use garage\templates\report\Small;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ReportController extends BaseController
{
    public function allowedAttributes()
    {
        $createForm = new ReportCreate();
        return [
            'create' => [
                $createForm->formName() => ['truckId', 'trailerId', 'type', 'driverId', 'dateTime', 'mileage', 'defLevel', 'fuelLevel']
            ],
            'sign' => [
                (new ReportSign())->formName() => ['flags'],
            ]
        ];
    }

    public function requiredAttributes()
    {
        $createForm = new ReportCreate();
        return [
            'create' => [
                $createForm->formName() => ['type', 'driverId', 'dateTime', 'mileage', 'defLevel', 'fuelLevel']
            ],
        ];
    }

    /**
     * @OA\Post(
     *     path="/report",
     *     tags={"report"},
     *     operationId="createReport",
     *     summary="createReport",
     *     requestBody={"$ref":"#/components/requestBodies/ReportCreate"},
     *     @OA\Response(
     *         response=200,
     *         description="successfull operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/ReportSmall"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionCreate()
    {
        $createForm = new ReportCreate();
        $createForm->load($this->getAllowedPost());
        if (!$createForm->validate()) {
            throw new HttpException(400, [$createForm->formName() => $createForm->getErrors()]);
        }

        $model = new Report();
        $model->type = $createForm->type;
        $model->truck_id = $createForm->truckId;
        $model->trailer_id = $createForm->trailerId;
        $model->driver_id = $createForm->driverId;
        $model->mileage = $createForm->mileage;
        $model->def_level = $createForm->defLevel;
        $model->fuel_level = $createForm->fuelLevel;
        $model->status = ReportStatus::DRAFT;
        $model->created_at = $createForm->timestamp;
        $this->saveModel($model);
        return $this->success($model->getAsArray(Small::class));
    }

    /**
     * @OA\Get(
     *     path="/report",
     *     tags={"report"},
     *     operationId="getReports",
     *     summary="getReports",
     *     @OA\Parameter(
     *         name="truckId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="trailerId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="page number (starts from 0)",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=0
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="page size",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successfull operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ReportSmall")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 ref="#/components/schemas/Pagination"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionIndex($truckId = 0, $trailerId = 0, $page = 0, $pageSize = 25)
    {
        $query = Report::find();
        if ($truckId) {
            $query->andWhere(['truck_id' => $truckId]);
        } elseif ($trailerId) {
            $query->andWhere(['trailer_id' => $trailerId]);
        }
        return $this->index($query, $page, $pageSize, Small::class);
    }

    /**
     * @OA\Get(
     *     path="/report/{id}",
     *     tags={"report"},
     *     operationId="getReport",
     *     summary="getReport",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successfull operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/ReportLarge"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        return $this->success($model->getAsArray(Large::class));
    }

    /**
     * @OA\Post(
     *     path="/report/{id}/sign",
     *     tags={"report"},
     *     operationId="signReport",
     *     summary="signReport",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     requestBody={"$ref":"#/components/requestBodies/ReportSign"},
     *     @OA\Response(
     *         response=200,
     *         description="successfull operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionSign($id)
    {
        $model = $this->findModel($id, ReportStatus::DRAFT);

        $form = new ReportSign();
        $form->load($this->getAllowedPost());
        $model->setScenario(Report::SCENARIO_SIGN);
        $model->signature_file = UploadedFile::getInstance($form, 'file');
        $form->validate();
        if (!$model->validate()) {
            $form->addError('file', $model->getFirstError('signature_file'));
        }
        if ($form->hasErrors()) {
            throw new HttpException(400, [$form->formName() => $form->getErrors()]);
        }

        Yii::$app->transaction->exec(function () use ($form, $model) {
            foreach ($form->flags as $flag) {
                $this->saveModel((new ReportFlag(['report_id' => $model->id, 'flag' => $flag])));
            }
            $model->status = ReportStatus::SIGNED;
            $this->saveModel($model);
            return true;
        });

        return $this->success();
    }

    /**
     * @OA\Delete(
     *     path="/report/{id}",
     *     tags={"report"},
     *     operationId="deleteReport",
     *     summary="deleteReport",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successfull operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */

    public function actionDelete($id) {
        $model = $this->findModel($id);
        if($model->status == ReportStatus::SIGNED) {
            throw new HttpException(400, Yii::t('app', "You can't delete this report, this report already signed"));
        }
        $reportMedia = ReportMedia::find()->where(['report_id'=>$model->id])->all();
        if($reportMedia) {
            foreach($reportMedia as $media) {
                if($media->file && $media->file != '') {
                    $filePath = Yii::getAlias('@cdn/web'). "/" . $media->file;
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
                ReportMedia::find()->where(['id'=>$media->id])->one()->delete();
            }
        }
        $model->delete();
        return $this->success();
    }

    protected function findModel($id, $status = null)
    {
        $condition = ['id' => $id];
        if ($status) {
            $condition['status'] = $status;
        }
        $model = Report::findOne($condition);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
