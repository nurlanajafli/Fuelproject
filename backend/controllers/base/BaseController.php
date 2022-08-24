<?php

namespace backend\controllers\base;

use common\controllers\traits\AllowedAttributes;
use common\controllers\traits\Reply;
use common\controllers\traits\RequiredAttributes;
use common\controllers\traits\SaveModel;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class BaseController extends Controller
{
    use AllowedAttributes;
    use RequiredAttributes;
    use SaveModel;
    use Reply;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => $this->accessRules(),
                ],
            ]
        );
    }

    protected function accessRules()
    {
        return [
            ['allow' => false, 'roles' => ['?']],
            ['allow' => true, 'roles' => ['@']],
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
}