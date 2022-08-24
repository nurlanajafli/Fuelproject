<?php

namespace frontend\controllers\base;

use common\actions\FormProcessingAction;
use common\controllers\traits\AllowedAttributes;
use common\controllers\traits\Reply;
use common\controllers\traits\RequiredAttributes;
use common\controllers\traits\SaveModel;
use common\models\TrackingLog;
use v1\components\HttpException;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class BaseController extends \common\controllers\BaseController
{
    use AllowedAttributes;
    use RequiredAttributes;
    use SaveModel;
    use Reply;

    public function behaviors()
    {
        $accessRules = $this->accessRules();
        if (!$accessRules) {
            $accessRules = [['allow' => true, 'roles' => ['@']]]; // TODO: temporary!
        }

        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => $accessRules,
                ],
            ]
        );
    }

    protected function accessRules()
    {
        return [];
    }

    /**
     * @return boolean
     * @var \yii\base\Model $model
     * @var \frontend\forms\SetLocation|array $formData
     */
    protected function addTrackingLog($formData, $model)
    {
        $form = is_array($formData) ? new \frontend\forms\SetLocation($formData) : $formData;
        $tlModel = new TrackingLog();
        $tlModel->date = $form->date;
        $tlModel->location_id = $form->location_id;
        $tlModel->zone = $form->zone;
        $reflect = new \ReflectionClass($model);
        $attr = strtolower($reflect->getShortName()) . '_id';
        $tlModel->$attr = $model->id;
        return $tlModel->save();
    }

    protected function setLocationActionConfig($str)
    {
        return [
            'class' => FormProcessingAction::class,
            'formClass' => '\frontend\forms\SetLocation',
            'view' => '@frontend/views/setLocation',
            'before' => function ($actionParams) use ($str) {
                $modelClass = ucfirst($str);
                $this->action->message = Yii::t('app', "It seems $modelClass's status is not Available");
                if (($str == 'unit') && ($actionParams['id'] == 0)) {
                    return true;
                }
                $find = call_user_func('\common\models\\' . $modelClass . '::find');
                return $find->andWhere([
                    'id' => $actionParams['id'],
                    'status' => ('\common\enums\\' . $modelClass . ($str == 'unit' ? 'Item' : '') . 'Status')::AVAILABLE
                ])->one();
            },
            'init' => function ($form, $model) use ($str) {
                $attribute = $str . '_id';
                $m = TrackingLog::find()->andWhere([
                    $attribute => (($model === true) ? 0 : $model->id)
                ])->orderBy(['id' => SORT_DESC])->one();
                if ($m) {
                    $form->location_id = $m->location_id;
                    $form->zone = $m->zone;
                }
                $formatter = Yii::$app->getFormatter();
                $form->date = $formatter->asDate('now', Yii::$app->params['formatter']['date']['db']);
            },
            'save' => function ($form, $model, $button) {
                /** @var FormProcessingAction $action */
                $action = $this->action;

                if ($model === true) {
                    return $action->saveResp(true, [$form->formName() => Yii::$app->request->post($form->formName())]);
                }

                $this->addTrackingLog($form, $model);
                return $action->saveResp();
            }
        ];
    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);
        if ($result && !$this->isHasAllRequiredAttributes()) {
            throw new BadRequestHttpException(Yii::t('app', 'Wrong params set'));
        }

        return $result;
    }

    protected function saveOrFail($model)
    {
        if (!$model->save()) {
            Yii::error([$model->formName() => $model->getErrors()]);
            throw new HttpException(500);
        }
    }
}