<?php

namespace frontend\controllers;

use common\models\ChatMessage;
use common\models\ChatMessageSeen;
use common\models\User;
use frontend\forms\chat\Send;
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

class ChatController extends base\BaseController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'rooms' => ChatMessage::rooms()
        ]);
    }

    public function actionView($id)
    {
        $rooms = ChatMessage::rooms();
        $array = array_filter($rooms, function ($room) use ($id) {
            return $id == $room['id'];
        });
        $room = array_shift($array);
        if (!$room) {
            throw new NotFoundHttpException();
        }

        $sendModel = new Send();
        if ($sendModel->load(Yii::$app->request->post()) && $sendModel->validate()) {
            $chatMessage = new ChatMessage();
            if ($room['load_id']) {
                $chatMessage->load_id = $room['load_id'];
            } else {
                $chatMessage->user_id = $room['user_id'];
            }
            $chatMessage->message = $sendModel->message;
            if ($this->saveModel($chatMessage)) {
                return $this->redirect(['view', 'id' => $room['id']]);
            }
        }

        /** @var ChatMessage[] $messages */
        $messages = ChatMessage::find()->
        alias('t0')->
        joinWith('chatMessageSeens')->
        andWhere(
            $room['load_id'] ?
                ['t0.load_id' => $room['load_id']] :
                new Expression('t0.load_id IS NULL AND (t0.user_id=:user OR t0.created_by=:user)', [':user' => $room['user_id']])
        )->
        orderBy(['t0.created_at' => SORT_ASC])->
        all();

        /** @var User $appUser */
        $appUser = Yii::$app->user->identity;
        foreach ($messages as $message) {
            if (!$message->isSeen) {
                $this->saveModel(new ChatMessageSeen(['message_id' => $message->id, 'user_id' => $appUser->id]));
            }
        }

        return $this->render('view', [
            'rooms' => $rooms,
            'id' => $id,
            'title' => $room['title'],
            'messages' => $messages,
            'sendModel' => $sendModel
        ]);
    }
}
