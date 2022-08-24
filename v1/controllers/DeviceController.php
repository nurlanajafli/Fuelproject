<?php

namespace v1\controllers;

use common\enums\OS;
use common\models\Device;
use Yii;
use yii\web\NotFoundHttpException;

class DeviceController extends BaseController
{
    public function allowedAttributes()
    {
        $model = new Device();
        return [
            'create' => [
                $model->formName() => ['id', 'os', 'version']
            ]
        ];
    }

    public function requiredAttributes()
    {
        $model = new Device();
        return [
            'create' => [
                $model->formName() => ['id', 'os', 'version']
            ]
        ];
    }

    /**
     * @OA\Post(
     *     path="/device",
     *     tags={"device"},
     *     operationId="deviceCreate",
     *     summary="Create device",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="Device",
     *                 type="object",
     *                 required={"id", "os", "version"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="os",
     *                     type="string",
     *                     enum={"IOS","Android"},
     *                 ),
     *                 @OA\Property(
     *                     property="version",
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
     *                 example=null
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
        $appUser = Yii::$app->user;
        $device = new Device();
        $device->user_id = $appUser->id;
        $device->load($this->getAllowedPost());
        Device::deleteAll(['id' => $device->id]);
        $this->saveModel($device, false);
        return $this->success();
    }

    /**
     * @OA\Delete(
     *     path="/device/{id}",
     *     tags={"device"},
     *     operationId="deviceDelete",
     *     summary="Delete device",
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
     *                 example=null
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
        $appUser = Yii::$app->user;
        Device::deleteAll(['id' => $id, 'user_id' => $appUser->id]);
        return $this->success();
    }

    /**
     * @OA\Patch(
     *     path="/device/{id}/attach",
     *     tags={"device"},
     *     operationId="deviceAttach",
     *     summary="Attach device",
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
     *                 example=null
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionAttach($id)
    {
        $appUser = Yii::$app->user;
        $device = Device::findOne(['id' => $id, 'os' => OS::IOS]);
        if (!$device) {
            throw new NotFoundHttpException();
        }

        $device->user_id = $appUser->id;
        $this->saveModel($device, false);
        return $this->success();
    }
}