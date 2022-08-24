<?php

namespace v1\templates\chatMessage;

use common\models\ChatMessage;
use TRS\RestResponse\templates\BaseTemplate;
use Yii;

/**
 * @OA\Schema(
 *     schema="ChatMessageSmall",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="reply_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="from_name",
 *         type="string",
 *         example="Doe, John"
 *     ),
 *     @OA\Property(
 *         property="from_me",
 *         type="boolean",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="seen",
 *         type="boolean",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         example="3/26/21 6:04 PM"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         example=null
 *     )
 * )
 */
class Small extends BaseTemplate
{
    protected function prepareResult()
    {
        /** @var ChatMessage $model */
        $model = $this->model;
        $this->result = [
            'id' => $model->id,
            'reply_id' => $model->reply_id,
            'message' => $model->message,
            'from_name' => $model->fromName,
            'from_me' => $model->isFromMe,
            'seen' => $model->isSeen,
            'created_at' => Yii::$app->formatter->asDatetime($model->created_at, Yii::$app->params['formats'][10]),
            'updated_at' => $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at, Yii::$app->params['formats'][10]) : null,
        ];
    }
}
