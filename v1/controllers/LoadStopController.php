<?php

namespace v1\controllers;

use common\enums\LoadMovementAction;
use common\enums\LoadStatus;
use common\enums\LoadStopStatus;
use common\enums\LoadStopType;
use common\helpers\Utils;
use common\models\LoadMovement;
use common\models\LoadNote;
use common\models\LoadStop;
use v1\components\HttpException;
use v1\forms\Arrival;
use v1\forms\Departure;
use v1\templates\loadStop\Large;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class LoadStopController extends BaseController
{
    public function allowedAttributes()
    {
        $arrivalForm = new Arrival();
        $departureForm = new Departure();
        return [
            'arrive' => [
                $arrivalForm->formName() => $arrivalForm->attributes()
            ],
            'depart' => [
                $departureForm->formName() => $departureForm->attributes()
            ],
        ];
    }

    public function requiredAttributes()
    {
        $arrivalForm = new Arrival();
        $departureForm = new Departure();
        return [
            'arrive' => [
                $arrivalForm->formName() => $arrivalForm->attributes()
            ],
            'depart' => [
                $departureForm->formName() => ['date', 'timeIn', 'timeOut']
            ],
        ];
    }

    /**
     * @OA\Get(
     *     path="/load-stop/{id}",
     *     summary="Get load stop details",
     *     tags={"load-stop"},
     *     operationId="showLoadStop",
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
     *                 ref="#/components/schemas/LoadStopLarge"
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
        $stop = $this->findModel($id, LoadStatus::ENROUTED);
        if (!$stop) {
            throw new NotFoundHttpException();
        }

        return $this->success($stop->getAsArray(Large::class));
    }

    /**
     * @OA\Patch(
     *     path="/load-stop/{id}/arrive",
     *     tags={"load-stop"},
     *     operationId="arriveLoadStop",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     requestBody={"$ref": "#/components/requestBodies/Arrival"},
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
    public function actionArrive($id)
    {
        $stop = $this->findModel($id, LoadStatus::ENROUTED);
        if (!$stop) {
            throw new NotFoundHttpException();
        }

        if ($stop->status) {
            throw new BadRequestHttpException();
        }

        $nm = $stop->getNeighboringModels();
        if ($nm[0] && $nm[0]->status != LoadStopStatus::DEPARTED) {
            throw new BadRequestHttpException();
        }

        $arrivalForm = new Arrival();
        $arrivalForm->load($this->getAllowedPost());
        if (!$arrivalForm->validate()) {
            throw new HttpException(400, [$arrivalForm->formName() => $arrivalForm->getErrors()]);
        }

        Yii::$app->transaction->exec(function () use ($stop, $arrivalForm) {
            $stop->status = LoadStopStatus::ARRIVED;
            $this->saveModel($stop);

            $lm = $stop->loadMovement ?: $this->initLoadMovement($stop);
            $lm->arrived_date = $arrivalForm->date;
            $lm->arrived_time_in = $arrivalForm->timeIn;
            $this->saveModel($lm);

            $loadNote = new LoadNote();
            $loadNote->load_id = $stop->load_id;
            $loadNote->notes = "Driver has arrived at the {$stop->stop_number}" . Utils::ordinal($stop->stop_number) . " stop";
            $this->saveModel($loadNote);
            return true;
        });

        return $this->success();
    }

    /**
     * @OA\Patch(
     *     path="/load-stop/{id}/depart",
     *     tags={"load-stop"},
     *     operationId="departLoadStop",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     requestBody={"$ref": "#/components/requestBodies/Departure"},
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
    public function actionDepart($id)
    {
        $stop = $this->findModel($id, LoadStatus::ENROUTED);
        if (!$stop) {
            throw new NotFoundHttpException();
        }

        if ($stop->status != LoadStopStatus::ARRIVED) {
            throw new BadRequestHttpException();
        }

        $departureForm = new Departure();
        $departureForm->load($this->getAllowedPost());
        if (!$departureForm->validate()) {
            throw new HttpException(400, [$departureForm->formName() => $departureForm->getErrors()]);
        }

        Yii::$app->transaction->exec(function () use ($stop, $departureForm) {
            $stop->status = LoadStopStatus::DEPARTED;
            $this->saveModel($stop);

            $lm = $stop->loadMovement ?: $this->initLoadMovement($stop);
            $lm->arrived_date = $departureForm->date;
            $lm->arrived_time_in = $departureForm->timeIn;
            $lm->arrived_time_out = $departureForm->timeOut;
            $lm->commodity_pieces = $departureForm->pieces;
            $lm->commodity_weight = $departureForm->weight;
            $lm->seal_no = $departureForm->seal_no;
            $lm->bol = $departureForm->bol;
            $this->saveModel($lm);

            $loadNote = new LoadNote();
            $loadNote->load_id = $stop->load_id;
            $loadNote->notes = "Driver has departed from the {$stop->stop_number}" . Utils::ordinal($stop->stop_number) . " stop";
            $this->saveModel($loadNote);
            return true;
        });

        return $this->success();
    }

    protected function findModel($id, $loadStatus = null)
    {
        $appUser = Yii::$app->user;
        $query = LoadStop::find()->alias('t')
            ->joinWith(['load l', 'company', 'loadMovement', 'load.dispatchAssignment da'])->andWhere(['t.id' => $id, 'da.driver_id' => $appUser->identity->driver->id]);
        if ($loadStatus)
            $query->andWhere(['l.status' => $loadStatus]);
        /** @var LoadStop $stop */
        $stop = $query->one();
        return $stop ?: false;
    }

    protected function initLoadMovement(LoadStop $stop)
    {
        $result = new LoadMovement();
        $result->action = ($stop->stop_type == LoadStopType::SHIPPER) ? LoadMovementAction::PICKUP : LoadMovementAction::DELIVER;
        $result->unit_id = $stop->load->dispatchAssignment->unit_id;
        $result->load_id = $stop->load->id;
        $result->load_stop_id = $stop->id;
        return $result;
    }
}