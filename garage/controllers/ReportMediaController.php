<?php

namespace garage\controllers;

use common\enums\ReportStatus;
use common\models\Report;
use common\models\ReportMedia;
use yii\web\NotFoundHttpException;

class ReportMediaController extends BaseController
{
    public function allowedAttributes()
    {
        $model = new ReportMedia();
        return [
            'create' => [
                $model->formName() => ['side', 'is_major', 'is_interior', 'description', 'damage_type', 'category_id']
            ]
        ];
    }

    /**
     * @OA\Post(
     *     path="/report/{id}/media",
     *     tags={"report"},
     *     operationId="postMedia",
     *     summary="postMedia",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="ReportMedia[file]",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[side]",
     *                     type="string",
     *                     enum={"Front","Left","Right","Back"}
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[is_major]",
     *                     type="integer",
     *                     enum={1,0}
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[is_interior]",
     *                     type="integer",
     *                     enum={1,0}
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[description]",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[damage_type]",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="ReportMedia[category_id]",
     *                     type="integer"
     *                 )
     *             )
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
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionCreate($id)
    {
        $report = Report::findOne(['id' => $id, 'status' => ReportStatus::DRAFT]);
        if (!$report) {
            throw new NotFoundHttpException();
        }

        $model = new ReportMedia();
        $model->setScenario(ReportMedia::SCENARIO_INSERT);
        $model->load($this->getAllowedPost());
        $model->report_id = $report->id;
        $this->saveModel($model);
        return $this->success();
    }
}