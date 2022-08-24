<?php

namespace v1\controllers;

use common\enums\LoadStatus;
use common\models\DispatchAssignment;
use common\models\Document;
use common\models\Load;
use common\models\State;
use v1\templates\document\Small as DocumentSmall;
use v1\templates\load\Large as LoadLarge;
use v1\templates\load\Mini as LoadMini;
use v1\templates\load\Small as LoadSmall;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class LoadController extends BaseController
{
    public function allowedAttributes()
    {
        $document = new Document();
        return [
            'upload' => [
                $document->formName() => ['code']
            ],
        ];
    }

    public function requiredAttributes()
    {
        $document = new Document();
        return [
            'upload' => [
                $document->formName() => ['code']
            ],
        ];
    }

    /**
     * @OA\Get(
     *     path="/load/dispatch",
     *     summary="Dispatched loads",
     *     tags={"load"},
     *     operationId="getDispatchedLoads",
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
     *                 @OA\Items(ref="#/components/schemas/LoadSmall")
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
    public function actionDispatch($page = 0, $pageSize = 25)
    {
        return $this->index(
            $this->getLoads(LoadStatus::ENROUTED),
            $page,
            $pageSize,
            LoadSmall::class,
            ['formatter' => Yii::$app->formatter]
        );
    }

    /**
     * @OA\Get(
     *     path="/load/history",
     *     summary="Arrived loads",
     *     tags={"load"},
     *     operationId="getArrivedLoads",
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
     *                 @OA\Items(ref="#/components/schemas/LoadSmall")
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
    public function actionHistory($page = 0, $pageSize = 25)
    {
        return $this->index(
            $this->getLoads(LoadStatus::COMPLETED),
            $page,
            $pageSize,
            LoadSmall::class,
            ['formatter' => Yii::$app->formatter]
        );
    }

    /**
     * @OA\Get(
     *     path="/load/reserved",
     *     summary="Reserved loads",
     *     tags={"load"},
     *     operationId="getReservedLoads",
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
     *                 @OA\Items(ref="#/components/schemas/LoadMini")
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
    public function actionReserved($page = 0, $pageSize = 25)
    {
        return $this->index(
            $this->getLoads(LoadStatus::RESERVED),
            $page,
            $pageSize,
            LoadMini::class,
            [
                'formatter' => Yii::$app->formatter,
                'states' => ArrayHelper::map(State::find()->all(), 'id', 'state_code')
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/load/{id}",
     *     summary="Get load details",
     *     tags={"load"},
     *     operationId="showLoad",
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
     *                 ref="#/components/schemas/LoadLarge"
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
        $appUser = Yii::$app->user;
        /** @var Load $load */
        $load = Load::find()->
        alias('t')->
        joinWith([
            'dispatchAssignment da',
            'dispatchAssignment.truck',
            'dispatchAssignment.truck.licenseState',
            'dispatchAssignment.truck.office',
            'dispatchAssignment.trailer',
            'dispatchAssignment.trailer.licenseState',
            'dispatchAssignment.trailer.office',
            'dispatchAssignment.trailer2'
        ])->
        where(['t.id' => $id, 'da.driver_id' => $appUser->identity->driver->id])->
        one();
        if (!$load) {
            throw new NotFoundHttpException();
        }

        return $this->success($load->getAsArray(LoadLarge::class));
    }

    /**
     * @OA\Post(
     *     path="/load/{id}/upload",
     *     summary="Upload image",
     *     tags={"load"},
     *     operationId="uploadLoadImage",
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
     *                     property="Document[file]",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 @OA\Property(
     *                     property="Document[code]",
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
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Document",
     *                     type="object",
     *                     ref="#/components/schemas/DocumentSmall"
     *                 )
     *             ),
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionUpload($id)
    {
        $appUser = Yii::$app->user;
        if (!DispatchAssignment::find()->
        joinWith('load')->
        where(['load_id' => $id, 'driver_id' => $appUser->identity->driver->id, 'status' => LoadStatus::ENROUTED])->
        exists()) {
            throw new NotFoundHttpException();
        }

        $documentModel = new Document();
        $documentModel->load($this->getAllowedPost());
        $documentModel->load_id = $id;
        if (ArrayHelper::isIn($_FILES[$documentModel->formName()]['type'][$documentModel->getImageAttribute()], Yii::$app->params['validDocMimeTypes'])) {
            $documentModel->setScenario(Document::SCENARIO_INSERT_1);
        } else {
            $documentModel->setScenario(Document::SCENARIO_INSERT_2);
        }
        $this->saveModel($documentModel);
        return $this->success([
            $documentModel->formName() => $documentModel->getAsArray(DocumentSmall::class)
        ]);
    }

    protected function getLoads($status)
    {
        $appUser = Yii::$app->user;
        return Load::find()
            ->alias('t')
            ->joinWith(['dispatchAssignment da', 'loadMovements']) // loadStops
            ->andWhere(['da.driver_id' => $appUser->identity->driver->id, 't.status' => $status]);
    }
}