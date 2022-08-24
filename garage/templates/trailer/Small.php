<?php

namespace garage\templates\trailer;

use common\models\Trailer;
use TRS\RestResponse\templates\BaseTemplate;

/**
 *
 * @OA\Schema(
 *     schema="TrailerSmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="year",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="make",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="model",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="tare",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="length",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="height",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="in_svc",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="serial",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="license",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="license_state",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="carb_id",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="office",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="notes",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="trailer_no",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="owned_by",
 *         type="object",
 *         ref="#/components/schemas/DriverSmall"
 *     )
 * )
 *
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var Trailer $model */
        $model = $this->model;
        $this->result = [
            'id' => $model->id,
            'type' => $model->type,
            'year' => $model->year,
            'make' => $model->make,
            'model' => $model->model,
            'tare' => $model->tare,
            'length' => $model->length,
            'height' => $model->height,
            'in_svc' => $model->in_svc,
            'serial' => $model->serial,
            'license' => $model->license,
            'license_state' => $model->licenseState ? $model->licenseState->state_code : null,
            'carb_id' => $model->carb_id,
            'office' => $model->office ? $model->office->office : null,
            'notes' => $model->notes,
            'trailer_no' => $model->trailer_no,
            'owned_by' => $model->ownedByDriver ? $model->ownedByDriver->getAsArray(\garage\templates\driver\Small::class) : null
        ];
    }
}
