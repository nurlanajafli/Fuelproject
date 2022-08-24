<?php

namespace garage\controllers;

use common\models\Driver;
use garage\templates\driver\Small;
use yii\db\Expression;

class DriverController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/driver",
     *     tags={"driver"},
     *     operationId="getDrivers",
     *     summary="getDrivers",
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
     *                 @OA\Items(ref="#/components/schemas/DriverSmall")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 ref="#/components/schemas/Pagination"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main": {}}
     *     }
     * )
     */
    public function actionIndex($query = '', $page = 0, $pageSize = 25)
    {
        return $this->index(Driver::find()->andWhere(new Expression("(concat_ws(', ', last_name, first_name) ILIKE :p0) OR (concat_ws(' ', last_name, first_name) ILIKE :p0)", ['p0' => "%$query%"])), $page, $pageSize, Small::class);
    }
}
