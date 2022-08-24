<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace frontend\controllers;

use common\enums\Permission;
use common\helpers\DateTime;
use common\models\Trailer;
use Exception;
use Yii;

/**
 * This is the class for controller "TrailerController".
 */
class TrailerController extends base\TrailerController
{

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'permissions' => [Permission::TRAILER_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update', 'delete', 'set-location'],
                'permissions' => [Permission::ADD_EDIT_TRAILERS]
            ],
        ];
    }

    public function actions()
    {
        return [
            'set-location' => $this->setLocationActionConfig('trailer')
        ];
    }

    public function actionCreate()
    {
        $model = new Trailer;
        $model->in_svc = DateTime::nowDateYMD();

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['index']);
            } elseif (!Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);
    }
}
