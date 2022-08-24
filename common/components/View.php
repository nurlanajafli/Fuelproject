<?php

namespace common\components;

use common\enums\SettingKey;
use common\models\DriverCompliance;
use common\models\Setting;
use Yii;
use yii\db\Expression;
use yii\web\View as BaseView;
use common\models\ChatMessage;

class View extends BaseView
{
    /** @inheritdoc */
    public function beginPage()
    {
        parent::beginPage();
        if (Yii::$app->id == 'app-frontend' && !Yii::$app->request->isAjax) {
            $this->params['notifications'] = ['items' => [], 'badge' => 0];
            $this->params['chat_messages'] = ChatMessage::getLastMessages(4);

            $array = [
                ['cdl_expires', Setting::get(SettingKey::NOTIFICATION_CDL)],
                ['next_dot_physical', Setting::get(SettingKey::NOTIFICATION_DOT)]
            ];

            foreach ($array as $value) {
                $column = "$value[0]_diff";
                /** @var DriverCompliance[] $rows */
                $rows = DriverCompliance::find()->
                alias('dc')->
                select("dc.*, ($value[0] - CURRENT_DATE) AS $column")->
                joinWith('driver')->
                andWhere(new Expression("($value[0] - CURRENT_DATE) <= $value[1]"))->
                all();
                foreach ($rows as $row) {
                    $dateTime = date_create('now', timezone_open(Yii::$app->formatter->timeZone));
//                    $dateTime->sub(new \DateInterval('P' . ($value[1] + ($row->$column < 0 ? -$row->$column : 0)) . 'D'));
                    $string = '';
                    if ($value[0] == 'cdl_expires') {
                        $string = $row->$column < 0
                            ? Yii::t(
                                'app',
                                '{name}: CDL expired {n,plural,=1{1 day} other{# days}} ago',
                                ['name' => $row->driver->getFullName(), 'n' => -$row->$column]
                            )
                            : Yii::t(
                                'app',
                                '{name}: CDL {n,plural,=0{expired today} =1{expires in 1 day} other{expires in # days}}',
                                ['name' => $row->driver->getFullName(), 'n' => $row->$column]
                            );
                    }
                    if ($value[0] == 'next_dot_physical') {
                        $string = $row->$column < 0
                            ? Yii::t(
                                'app',
                                '{name}: DOT Physical test results expired {n,plural,=1{1 day} other{# days}} ago',
                                ['name' => $row->driver->getFullName(), 'n' => -$row->$column]
                            )
                            : Yii::t(
                                'app',
                                '{name}: DOT Physical test results {n,plural,=0{expired today} =1{expires in 1 day} other{expires in # days}}',
                                ['name' => $row->driver->getFullName(), 'n' => $row->$column]
                            );
                    }

                    if ($this->params['notifications']['badge'] == 3) {
                        $this->params['notifications']['badge'] .= '+';
                        break 2;
                    }

                    $this->params['notifications']['items'][] = [
                        'fa-exclamation-triangle',
                        Yii::$app->formatter->asDate($dateTime, Yii::$app->params['formats'][8]),
                        $string
                    ];
                    $this->params['notifications']['badge']++;
                }
            }
        }
    }

    /** @inheritdoc */
    public function endPage($ajaxMode = false)
    {
        if ($ajaxMode) {
            $this->jsFiles = [];
        }
        parent::endPage($ajaxMode);
    }
}
