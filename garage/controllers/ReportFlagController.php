<?php

namespace garage\controllers;

use common\enums\ReportFlag;

class ReportFlagController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/report-flag",
     *     tags={"report-flag"},
     *     operationId="getReportFlags",
     *     summary="getReportFlags",
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
        foreach (ReportFlag::getUiEnums() as $k => $v) {
            $data[] = ['id' => $k, 'description' => $v];
        }
        return $this->success($data);
    }
}