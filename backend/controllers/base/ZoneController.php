<?php
/**
 * /var/www/html/backend/runtime/giiant/358b0e44f1c1670b558e36588c267e47
 *
 * @package default
 */


// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\controllers\base;

use common\models\Zone;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;

/**
 * ZoneController implements the CRUD actions for Zone model.
 */
class ZoneController extends BaseController
{


	/**
	 *
	 * @var boolean whether to enable CSRF validation for the actions in this controller.
	 * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
	 */
	public $enableCsrfValidation = false;


	/**
	 * Lists all Zone models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new \yii\data\ActiveDataProvider([
				'query' => Zone::find(),
			]);

		Tabs::clearLocalStorage();

		Url::remember();
		\Yii::$app->session['__crudReturnUrl'] = null;

		return $this->render('index', [
				'dataProvider' => $dataProvider,
			]);
	}


	/**
	 * Displays a single Zone model.
	 *
	 * @param string  $code
	 * @return mixed
	 */
	public function actionView($code) {
		\Yii::$app->session['__crudReturnUrl'] = Url::previous();
		Url::remember();
		Tabs::rememberActiveState();

		return $this->render('view', [
				'model' => $this->findModel($code),
			]);
	}


	/**
	 * Creates a new Zone model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Zone;

		try {
			if ($model->load($_POST) && $model->save()) {
				return $this->redirect(['view', 'code' => $model->code]);
			} elseif (!\Yii::$app->request->isPost) {
				$model->load($_GET);
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			$model->addError('_exception', $msg);
		}
		return $this->render('create', ['model' => $model]);
	}


	/**
	 * Updates an existing Zone model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param string  $code
	 * @return mixed
	 */
	public function actionUpdate($code) {
		$model = $this->findModel($code);

		if ($model->load($_POST) && $model->save()) {
			return $this->redirect(Url::previous());
		} else {
			return $this->render('update', [
					'model' => $model,
				]);
		}
	}


	/**
	 * Deletes an existing Zone model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param string  $code
	 * @return mixed
	 */
	public function actionDelete($code) {
		try {
			$this->findModel($code)->delete();
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			\Yii::$app->getSession()->addFlash('error', $msg);
			return $this->redirect(Url::previous());
		}


		// TODO: improve detection
		$isPivot = strstr('$code', ',');
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


	/**
	 * Finds the Zone model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @throws HttpException if the model cannot be found
	 * @param string  $code
	 * @return Zone the loaded model
	 */
	protected function findModel($code) {
		if (($model = Zone::findOne($code)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}


}
