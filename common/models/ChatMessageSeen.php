<?php

namespace common\models;

use \common\models\base\ChatMessageSeen as BaseChatMessageSeen;
use common\helpers\DateTime;

/**
 * This is the model class for table "chat_message_seen".
 */
class ChatMessageSeen extends BaseChatMessageSeen
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
