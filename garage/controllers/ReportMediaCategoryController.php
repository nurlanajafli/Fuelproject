<?php

namespace garage\controllers;

use common\models\ReportMediaCategory;
use garage\components\HttpException;
use garage\templates\reportMediaCategory\Small;
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

class ReportMediaCategoryController extends BaseController
{
    public function allowedAttributes()
    {
        return [
            'create' => [
                'MediaCategory' => ['name', 'description']
            ],
            'update' => [
                'MediaCategory' => ['name', 'description']
            ],
        ];
    }

    /**
     * @OA\Get(
     *     path="/media-category",
     *     tags={"media-category"},
     *     operationId="getMediaCategories",
     *     summary="getMediaCategories",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=false,
     *         description="search query",
     *         @OA\Schema(
     *             type="string"
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
     *                 @OA\Items(ref="#/components/schemas/ReportMediaCategorySmall")
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
    public function actionIndex($query = '', $page = 0, $pageSize = 25)
    {
        return $this->index(ReportMediaCategory::find()->andWhere(new Expression('(name ILIKE :p0) OR (description ILIKE :p0)', ['p0' => "%$query%"])), $page, $pageSize, Small::class);
    }

    /**
     * @OA\Post(
     *     path="/media-category",
     *     tags={"media-category"},
     *     operationId="createMediaCategory",
     *     summary="createMediaCategory",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="MediaCategory",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
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
    public function actionCreate()
    {
        $model = new ReportMediaCategory();
        $model->load($this->getAllowedPost(), 'MediaCategory');
        $this->saveModel($model);
        return $this->success();
    }

    /**
     * @OA\Patch(
     *     path="/media-category/{id}",
     *     tags={"media-category"},
     *     operationId="updateMediaCategory",
     *     summary="updateMediaCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="MediaCategory",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load($this->getAllowedPost(), 'MediaCategory');
        $this->saveModel($model);
        return $this->success();
    }

    /**
     * @OA\Delete(
     *     path="/media-category/{id}",
     *     tags={"media-category"},
     *     operationId="deleteMediaCategory",
     *     summary="deleteMediaCategory",
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
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->getReportMedia()->count()) {
            throw new HttpException(400, Yii::t('app', 'The category is not empty'));
        }
        $model->delete();
        return $this->success();
    }

    protected function findModel($id)
    {
        $model = ReportMediaCategory::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}