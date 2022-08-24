<?php
/**
 * /var/www/html/backend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace backend\controllers;

use backend\models\ProfileForm;
use common\models\User;
use dmstr\bootstrap\Tabs;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * This is the class for controller "UserController".
 */
class UserController extends \backend\controllers\base\UserController
{
    public function actionIndex()
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => User::find()->joinWith(['defaultOffice', 'department'])->andWhere(['<>', 'status', User::STATUS_DELETED]),
        ]);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new User;
        $profile = new ProfileForm();
        $profile->setUser($model);

        try {
            if ($profile->load($_POST) && $profile->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } elseif (!\Yii::$app->request->isPost) {
                $profile->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $profile->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $profile]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $profile = new ProfileForm();
        $profile->setUser($model);

        if ($profile->load($_POST) && $profile->save()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                'model' => $profile,
            ]);
        }
    }

    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            $model->status = User::STATUS_DELETED;
            $model->save();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }


        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = User::find()->andWhere(['id' => $id])->andWhere(['<>', 'status', User::STATUS_DELETED])->one()) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
