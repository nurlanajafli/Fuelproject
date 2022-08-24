<?php

namespace common\components;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class Firebase extends \yii\base\Component
{
    public $credentialsPath;

    protected $messaging;

    public function init()
    {
        $factory = (new Factory)
            //->withDatabaseUri('https://my-project-default-rtdb.firebaseio.com')
            ->withServiceAccount($this->credentialsPath);

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, $imageUrl = null, $data = [])
    {
        $array = [
            'title' => $title,
            'body' => $body,
        ];

        if ($imageUrl) {
            $array['image'] = $imageUrl;
        }

        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => $array,
            'data' => $data
        ]);
        try {
            $this->messaging->send($message);
        } catch (\Exception $exception) {
        }
    }
}