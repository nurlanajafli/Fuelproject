<?php

namespace garage\controllers;

use common\enums\DamageType;

class DamageTypeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/damage-type",
     *     tags={"damage-type"},
     *     operationId="getDamageTypes",
     *     summary="getDamageTypes",
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
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
    */
    public function actionIndex()
    {
        $data = [];
        foreach (DamageType::getUiEnums() as $k => $v) {
            $data[] = ['id' => $k, 'description' => $v];
        }
        return $this->success($data);
    }
}