<?php

namespace v1\controllers;

use common\enums\LoadStatus;
use common\models\ChatMessage;
use common\models\ChatMessageSeen;
use v1\components\oauth2\AppIdentity;
use v1\templates\chatMessage\Small;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ChatMessageController extends BaseController
{
    public function allowedAttributes()
    {
        $chatMessage = new ChatMessage();
        return [
            'create' => [
                $chatMessage->formName() => ['reply_id', 'load_id', 'message']
            ],
            'update' => [
                $chatMessage->formName() => ['id', 'message']
            ],
        ];
    }

    public function requiredAttributes()
    {
        $chatMessage = new ChatMessage();
        return [
            'create' => [
                $chatMessage->formName() => ['message']
            ],
            'update' => [
                $chatMessage->formName() => ['id', 'message']
            ],
        ];
    }

    /**
     * @OA\Get(
     *     path="/chat-message",
     *     tags={"chat-message"},
     *     operationId="chatMessageIndex",
     *     summary="Get list of chat messages",
     *     @OA\Parameter(
     *         name="loadId",
     *         in="query",
     *         required=false,
     *         description="",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="page number (starts from 0). Empty value means that page should be set automaticaly",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=-1
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="page size",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1
     *         ),
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
     *                 @OA\Items(ref="#/components/schemas/ChatMessageSmall")
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
    public function actionIndex(int $loadId = null, int $page = null, int $pageSize = 25)
    {
        /** @var AppIdentity $appUser */
        $appUser = Yii::$app->user->identity;
        if (is_null($loadId)) {
            $where = new Expression('t0.load_id IS NULL AND (t0.user_id=:user OR t0.created_by=:user)', [':user' => $appUser->id]);
        } else {
            $where = new Expression(
                't0.load_id=:load AND t1.status=:status AND (t2.driver_id=:driver OR t2.codriver_id=:driver)',
                [':load' => $loadId, ':status' => LoadStatus::ENROUTED, ':driver' => $appUser->driver->id]
            );
        }

        $q = ChatMessage::find()
            ->alias('t0')
            ->joinWith(['load t1', 'load.dispatchAssignment t2', 'chatMessageSeens', 'createdBy.driver'])
            ->andWhere($where)
            ->orderBy(['t0.created_at' => SORT_ASC]);

        if (is_null($page)) {
            /** @var ChatMessage[] $messages */
            $messages = $q->all();
            $i = 0;
            foreach ($messages as $message) {
                $i++;
                if (!$message->isSeen) {
                    break;
                }
            }
            $page = ceil($i / $pageSize) - 1;
        }

        return $this->index(
            $q,
            $page,
            $pageSize,
            Small::class
        );
    }

    /**
     * @OA\Post(
     *     path="/chat-message",
     *     tags={"chat-message"},
     *     operationId="chatMessageCreate",
     *     summary="Send new message to channel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="ChatMessage",
     *                 type="object",
     *                 required={"message"},
     *                 @OA\Property(
     *                     property="reply_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="load_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
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
     *                 ref="#/components/schemas/ChatMessageSmall"
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
        $post = $this->getAllowedPost();
        $chatMessage = new ChatMessage();
        $chatMessage->load($post);
        $this->saveModel($chatMessage);
        return $this->success($chatMessage->getAsArray(Small::class));
    }

    /**
     * @OA\Patch(
     *     path="/chat-message",
     *     tags={"chat-message"},
     *     operationId="chatMessageUpdate",
     *     summary="Edit message",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="ChatMessage",
     *                 type="object",
     *                 required={"id", "message"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
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
     *                 ref="#/components/schemas/ChatMessageSmall"
     *             )
     *         )
     *     ),
     *     security={
     *         {"main":{}}
     *     }
     * )
     */
    public function actionUpdate()
    {
        $post = $this->getAllowedPost();
        $chatMessage = new ChatMessage();
        $chatMessage = ChatMessage::findOne(ArrayHelper::getValue($post, sprintf('%s.id', $chatMessage->formName())));
        if (!$chatMessage) {
            throw new NotFoundHttpException();
        }

        $chatMessage->load($post);
        $this->saveModel($chatMessage);
        return $this->success($chatMessage->getAsArray(Small::class));
    }

    /**
     * @OA\Patch(
     *     path="/chat-message/{id}/read",
     *     tags={"chat-message"},
     *     operationId="chatMessageRead",
     *     summary="Mark message as read",
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
    public function actionRead($id)
    {
        /** @var AppIdentity $appUser */
        $appUser = Yii::$app->user->identity;

        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::findOne($id);
        if (!$chatMessage) throw new NotFoundHttpException();

        if ($chatMessage->load_id) {
            $where = new Expression(
                't0.load_id=:load AND t1.status=:status AND (t2.driver_id=:driver OR t2.codriver_id=:driver)',
                [':load' => $chatMessage->load_id, ':status' => LoadStatus::ENROUTED, ':driver' => $appUser->driver->id]
            );
        } else {
            $where = new Expression(
                't0.load_id IS NULL AND t0.user_id=:user',
                [':user' => $appUser->id]
            );
        }

        /** @var ChatMessage[] $chatMessages */
        $chatMessages = ChatMessage::find()
            ->alias('t0')
            ->joinWith(['load t1', 'load.dispatchAssignment t2', 'chatMessageSeens'])
            ->andWhere($where)
            ->andWhere('t0.id <= :id AND t0.created_by<>:user AND ' . ChatMessageSeen::tableName() . '.message_id IS NULL', [':id' => $chatMessage->id, ':user' => $appUser->id])
            ->orderBy(['t0.id' => SORT_ASC])
            ->all();

        foreach ($chatMessages as $message) {
            $this->saveModel(new ChatMessageSeen(['message_id' => $message->id, 'user_id' => $appUser->id]));
        }

        return $this->success();
    }
}
