<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\LoadStatus;
use common\models\ChatMessage;
use common\models\Load;
use frontend\controllers\base\BaseController;
use frontend\forms\chatMessage\Create;

class ChatMessageController extends BaseController
{
    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'formClass' => Create::class,
                'before' => function ($actionParams) {
                    return Load::find()
                        ->alias('t')
                        ->joinWith('dispatchAssignment.driver.user.devices')
                        ->where(['t.id' => $actionParams['id'], 't.status' => LoadStatus::ENROUTED])
                        ->one();
                },
                'viewParams' => function ($model) {
                    /**
                     * @var Load $model
                     */
                    return ['fullName' => $model->dispatchAssignment->driver->getFullName()];
                },
                'save' => function (Create $form, Load $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $chatMessage = new ChatMessage();
                    $chatMessage->load_id = $model->id;
                    $chatMessage->message = $form->message;

                    return $action->saveResp($this->saveModel($chatMessage));
                }
            ]
        ];
    }
}
