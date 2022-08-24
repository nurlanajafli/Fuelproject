<?php

namespace common\models;

use common\enums\CronStatus;
use common\enums\CronTask;
use common\helpers\DateTime;
use common\models\base\Cron as BaseCron;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cron".
 */
class Cron extends BaseCron
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['task'], 'in', 'range' => CronTask::getEnums()],
                [['status'], 'in', 'range' => CronStatus::getEnums()],
                [['attempts'], 'default', 'value' => 0]
            ]
        );
    }

    /**
     * @param array|string|null $data
     */
    public static function create(string $task, $data, int $sleep, int $maxRetryCount, int $nextAttemptInterval): bool
    {
        $model = new Cron();
        $model->task = $task;
        $model->data = $data ? json_encode($data) : null;
        if ($model->data === false) {
            $model->data = null;
        }
        $model->start_time = new Expression("localtimestamp + interval '$sleep seconds'");
        $model->max_retry_count = $maxRetryCount;
        $model->next_attempt_interval = $nextAttemptInterval;
        return $model->save();
    }
}
