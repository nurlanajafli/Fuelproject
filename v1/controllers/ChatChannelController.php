<?php

namespace v1\controllers;

use common\models\ChatMessage;
use v1\templates\chatMessage\Mini;

class ChatChannelController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/chat-channel",
     *     tags={"chat-channel"},
     *     operationId="chatChannelIndex",
     *     summary="Get list of chat channels",
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
     *                         property="title",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="load_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="unread_count",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="last_message",
     *                         type="object",
     *                         ref="#/components/schemas/ChatMessageMini"
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
        return $this->success(ChatMessage::rooms(Mini::class));
    }
}
