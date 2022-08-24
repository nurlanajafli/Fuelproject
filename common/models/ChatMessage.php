<?php

namespace common\models;

use common\enums\LoadStatus;
use common\helpers\DateTime;
use common\helpers\EventHelper;
use common\helpers\Utils;
use common\models\base\ChatMessage as BaseChatMessage;
use common\models\traits\Template;
use Yii;
use yii\base\Event;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "chat_message".
 *
 * @property-read bool $isFromMe
 * @property-read bool $isSeen
 * @property-read string $fromName
 * @property-read array $options
 */
class ChatMessage extends BaseChatMessage
{
    use Template;

    public $interval1;

    public function init()
    {
        parent::init();

        $this->on(static::EVENT_BEFORE_VALIDATE, [$this, 'checkAccess']);
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            Utils::removeAttributeRules(parent::rules(), 'message', ['required']),
            [
                [['reply_id'], function ($attribute, $params) {
                    if (!$this->hasErrors($attribute) && ($this->getAttribute($attribute) == $this->id)) {
                        $this->addError($attribute, Yii::t('app', 'Can not be the same as Id'));
                    }
                }],
                [['load_id'], function ($attribute, $params) {
                    if (!$this->hasErrors($attribute) && $this->load_id) {
                        $user = Yii::$app->getUser()->getIdentity();
                        $dispatchAssignment = $this->load->dispatchAssignment;
                        /** @var Driver $driver */
                        if (
                            !$dispatchAssignment
                            || (($driver = Driver::find()->where(['user_id' => $user->getId()])->one()) && !ArrayHelper::isIn($driver->id, [$dispatchAssignment->driver_id, $dispatchAssignment->codriver_id]))
                            || (!$driver && (count(User::find()->select('department_id')->where(['id' => [$user->getId(), $dispatchAssignment->created_by]])->groupBy('department_id')->all()) != 1))
                        ) {
                            $this->addError($attribute, Yii::t('app', 'You can\'t write to this channel'));
                        }
                    }
                }],
                [['message'], 'trim'],
                [['message'], 'filter', 'filter' => 'strip_tags'],
                [['message'], 'required'],
            ]
        );
    }

    public function checkAccess(Event $event)
    {
        $model = $event->sender;
        if ($model->id) {
            if ($model->created_by != Yii::$app->user->id) {
                $model->addError('id', Yii::t('app', 'You cannot edit this message'));
            }
            if (EventHelper::attributeIsChanged($event, 'load_id')) {
                $model->addError('load_id', Yii::t('app', 'Messages can\'t be moved between loads'));
            }
        }
    }

    public function getChatMessageSeens()
    {
        return parent::getChatMessageSeens()->onCondition([ChatMessageSeen::tableName() . '.user_id' => Yii::$app->user->id]);
    }

    public static function rooms($template = null)
    {
        /** @var User $appUser */
        $appUser = Yii::$app->user->identity;
        $result = [];
        if ($appUser->driver) {
            $filter = '(t0.load_id IS NOT NULL AND t0.created_by<>:user) OR (t0.load_id IS NULL AND t0.user_id=:user)';
            $loadsCond = 't2.status=:status AND (t3.driver_id=:driver OR t3.codriver_id=:driver)';
            $loadsCondParams = [':status' => LoadStatus::ENROUTED, ':driver' => $appUser->driver->id];
            $where = new Expression(
                "(t0.load_id IS NULL AND (t0.user_id=:user OR t0.created_by=:user)) OR (t0.load_id IS NOT NULL AND ($loadsCond))",
                ArrayHelper::merge([':user' => $appUser->id], $loadsCondParams)
            );
            $groupBy = '';
        } else {
            $filter = 't0.created_by<>:user';
            $officeId = $appUser->default_office_id;
            $subQuery = '(SELECT user_id FROM ' . Driver::tableName() . ' WHERE office_id=:office)';
            $loadsCond = 't2.status=:status AND (t4.office_id=:office OR t5.office_id=:office)';
            $loadsCondParams = [':status' => LoadStatus::ENROUTED, ':office' => $officeId];
            $where = new Expression(
                "(t0.load_id IS NULL AND (t0.user_id IN $subQuery OR t0.created_by IN $subQuery)) OR (t0.load_id IS NOT NULL AND ($loadsCond))",
                ArrayHelper::merge([':office' => $officeId], $loadsCondParams)
            );
            $groupBy = '(CASE WHEN t0.load_id IS NOT NULL THEN NULL ELSE COALESCE(t0.user_id,t0.created_by) END)';
        }
        $rows = (new Query())->
        from(['t0' => static::tableName()])->
        leftJoin(['t1' => ChatMessageSeen::tableName()], 't1.message_id=t0.id AND t1.user_id=:user', [':user' => $appUser->id])->
        leftJoin(['t2' => Load::tableName()], 't2.id=t0.load_id')->
        leftJoin(['t3' => DispatchAssignment::tableName()], 't3.load_id=t2.id')->
        leftJoin(['t4' => Driver::tableName()], 't4.id=t3.driver_id')->
        leftJoin(['t5' => Driver::tableName()], 't5.id=t3.codriver_id')->
        select(new Expression("t0.load_id,max(t0.id) as id_max,(count(*) FILTER (WHERE $filter AND t1.message_id IS NULL)) AS unread_count", [':user' => $appUser->id]))->
        andWhere($where)->
        groupBy(new Expression('t0.load_id' . ($groupBy ? ",$groupBy" : '')))->
        orderBy(['id_max' => SORT_DESC])->
        all();
        foreach ($rows as $row) {
            /** @var ChatMessage $lastMessage */
            $lastMessage = static::find()->alias('t0')->joinWith(['createdBy.driver', 'user.driver'])->andWhere(['t0.id' => $row['id_max']])->one();
            $options = $lastMessage->options;
            $result[] = ArrayHelper::merge([
                'load_id' => $row['load_id'],
                'unread_count' => $row['unread_count'],
                'last_message' => $template ? $lastMessage->getAsArray($template) : $lastMessage,
                'title' => $options['room_title']
            ], !$appUser->driver ? ['user_id' => $options['user_id'], 'id' => $options['room_id']] : []);
        }
        /** @var Load[] $loads */
        $loads = Load::find()->
        alias('t2')->
        joinWith(['dispatchAssignment t3', 'dispatchAssignment.driver t4', 'dispatchAssignment.codriver t5'])->
        andWhere(new Expression($loadsCond, $loadsCondParams))->
        andWhere(['NOT IN', 't2.id', array_map(function ($room) {
            return $room['load_id'] + 0;
        }, $result)])->
        all();
        foreach ($loads as $load) {
            $result[] = ArrayHelper::merge([
                'load_id' => $load->id,
                'unread_count' => 0,
                'last_message' => null,
                'title' => Yii::t('app', 'Load {id}', ['id' => $load->id])
            ], !$appUser->driver ? ['user_id' => null, 'id' => md5("load_{$load->id}")] : []);
        }
        return $result;
    }

    public static function getLastMessages($limit)
    {
        /** @var User $appUser */
        $appUser = Yii::$app->user->identity;

        $officeId = $appUser->default_office_id;
        $subQuery = '(SELECT user_id FROM ' . Driver::tableName() . ' WHERE office_id=:office)';
        $loadsCond = 't2.status=:status AND (t4.office_id=:office OR t5.office_id=:office)';
        $loadsCondParams = [':status' => LoadStatus::ENROUTED, ':office' => $officeId];
        $where = new Expression(
            "(t0.created_by<>:user AND t1.message_id IS NULL) AND ((t0.load_id IS NULL AND (t0.user_id IN $subQuery OR t0.created_by IN $subQuery)) OR (t0.load_id IS NOT NULL AND ($loadsCond)))",
            ArrayHelper::merge([':office' => $officeId, ':user' => $appUser->id], $loadsCondParams)
        );

        Yii::$app->db->createCommand('SET intervalstyle=postgres_verbose')->execute();
        $rows = static::find()->
        select('t0.*, (LOCALTIMESTAMP - t0.created_at) AS interval1')->
        alias('t0')->
        joinWith('createdBy.driver')->
        leftJoin(['t1' => ChatMessageSeen::tableName()], 't1.message_id=t0.id AND t1.user_id=:user', [':user' => $appUser->id])->
        leftJoin(['t2' => Load::tableName()], 't2.id=t0.load_id')->
        leftJoin(['t3' => DispatchAssignment::tableName()], 't3.load_id=t2.id')->
        leftJoin(['t4' => Driver::tableName()], 't4.id=t3.driver_id')->
        leftJoin(['t5' => Driver::tableName()], 't5.id=t3.codriver_id')->
        andWhere($where)->
        orderBy(['t0.created_at' => SORT_ASC])->
        all();
        return ['total_count' => count($rows), 'messages' => array_slice($rows, 0, $limit)];
    }

    public function getIsFromMe()
    {
        /** @var User $appUser */
        $appUser = Yii::$app->user->identity;
        return $appUser->id == $this->created_by;
    }

    public function getIsSeen()
    {
        return $this->isFromMe || count($this->chatMessageSeens);
    }

    public function getFromName()
    {
        return $this->createdBy->driver ? $this->createdBy->driver->getFullName() : $this->createdBy->getFullName();
    }

    public function getOptions()
    {
        /** @var User $appUser */
        $appUser = Yii::$app->user->identity;
        $result = [];
        $result['user_id'] = $this->load_id ? null : ($this->createdBy->driver ? $this->created_by : $this->user_id);
        $result['room_id'] = md5($this->load_id ? "load_{$this->load_id}" : "user_{$result['user_id']}");
        if ($this->load_id) {
            $result['room_title'] = Yii::t('app', 'Load {id}', ['id' => $this->load_id]);
        } elseif ($appUser->driver) {
            $result['room_title'] = Yii::t('app', 'Support');
        } elseif ($this->createdBy->driver) {
            $result['room_title'] = $this->createdBy->driver->getFullName();
        } elseif ($this->user->driver) {
            $result['room_title'] = $this->user->driver->getFullName();
        }
        return $result;
    }
}
