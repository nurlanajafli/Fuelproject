<?php

namespace console\controllers;

use common\enums\CronStatus;
use common\enums\CronTask;
use common\models\Cron;
use common\models\Load;
use yii\console\Controller;
use yii\db\Expression;

class CronController extends Controller
{
    public function actionRun()
    {
        /** @var Cron[] $rows */
        $rows = Cron::find()->
        andWhere(['status' => [CronStatus::_NEW, CronStatus::RETRY]])->
        andWhere(new Expression('attempts < max_retry_count'))->
        andWhere(new Expression('start_time <= localtimestamp'))->
        all();
        foreach ($rows as $row) {
            $err = null;
            $row->status = CronStatus::IN_PROGRESS;
            $row->save();
            $data = json_decode($row->data);
            switch ($row->task) {
                case CronTask::DELETE_FILE:
                    try {
                        $err = !unlink($data->filename);
                    } catch (\Exception $exception) {
                        $err = $exception->getMessage();
                    }
		            break;
                case CronTask::DELETE_EMPTY_LOAD:
                    $load = Load::findOne($data->id);
                    if ($load && $load->isEmpty()) {
                        try {
                            if (!$load->delete()) {
                                $err = 'Unable to delete';
                            }
                        } catch (\Exception $exception) {
                            $err = $exception->getMessage();
                        }
                    }
                    break;
            }
            $row->attempts++;
            $row->status = CronStatus::DONE;
            if ($err && ($row->attempts < $row->max_retry_count)) {
                $row->status = CronStatus::RETRY;
                $row->start_time = new Expression("start_time + interval '{$row->next_attempt_interval} seconds'");
            }
            $row->save();
        }
    }
}
