<?php

namespace common\actions;

use Yii;
use yii\base\Action;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\HttpException;
use yii\web\Response;

class FormProcessingAction extends Action
{
    const EVENT_AFTER_LOAD = 'fpaAfterLoad';

    public $form;
    public $formClass;
    public $before;
    public $init;
    public $save;
    public $view;
    public $viewParams;
    public $statusCode = 404;
    public $message;
    public $viewOnBeforeFail;
    public $viewParamsOnBeforeFail = [];

    public $validateResp;

    public function run($id = 0)
    {
        $beforeFunc = $this->before;
        $beforeRes = null;
        if ($beforeFunc && !($beforeRes = $beforeFunc($this->controller->actionParams))) {
            return $this->viewOnBeforeFail ?
                $this->controller->renderAjax($this->viewOnBeforeFail, $this->viewParamsOnBeforeFail) :
                $this->saveResp(false, [], null, $this->statusCode);
        }

        $getForm = $this->form ?: function ($beforeRes) {
            $formClass = $this->formClass;
            if (is_callable($formClass)) {
                $formClass = $formClass($beforeRes);
            }
            return new $formClass();
        };

        /** @var \yii\base\Model|string $form */
        $form = is_callable($getForm) ? $getForm($beforeRes) : $getForm;
        if ($form === 'proxy') {
            $form = $beforeRes;
        }

        if (Yii::$app->request->isPost) {

            if (method_exists($this->controller, 'getAllowedPost')) {
                $m = new \ReflectionMethod($this->controller, 'getAllowedPost');
                $m->setAccessible(true);
                $form->load($m->invoke($this->controller));
                $m->setAccessible(false);
            } else $form->load(Yii::$app->request->post());
            $form->trigger(static::EVENT_AFTER_LOAD);

            $submitButtonCode = Yii::$app->request->post('SubmitButtonCode');

            if (!Yii::$app->request->isPjax) {
                if (Yii::$app->request->isAjax) {
                    $validationErrors = ActiveForm::validate($form);
                    if (!$submitButtonCode || $validationErrors) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ArrayHelper::merge(
                            $validationErrors,
                            $this->validateResp ? ['fpa_fields' => call_user_func($this->validateResp, $form, $validationErrors)] : []
                        );
                    }
                    return $this->save ? call_user_func($this->save, $form, $beforeRes, $submitButtonCode) : $this->saveResp();
                }
                if ($form->validate()) {
                    return $this->save ? call_user_func($this->save, $form, $beforeRes, $submitButtonCode) : $this->saveResp();
                }
            } elseif ($form->validate() && $submitButtonCode) {
                return $this->save ? call_user_func($this->save, $form, $beforeRes, $submitButtonCode) : $this->saveResp();
            }
        }

        if ($initFunc = $this->init) {
            $initFunc($form, $beforeRes);
        }

        $view = $this->view ?: lcfirst(Inflector::id2camel($this->controller->action->id));
        $formConfig = [
            'id' => uniqid('auto-'),
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'fieldConfig' => Yii::$app->params['activeForm']['fieldConfig'],
            'options' => [
                'class' => 'js-fp',
            ]
        ];
        $params = ['model' => $form, 'formConfig' => $formConfig];
        if ($this->viewParams) {
            if (is_callable($this->viewParams)) {
                $viewParams = $this->viewParams;
                $params = array_merge($params, $viewParams($beforeRes));
            } elseif (is_array($this->viewParams)) {
                $params = array_merge($params, $this->viewParams);
            }
        }

        return Yii::$app->request->isAjax
            ? $this->controller->renderAjax($view, $params)
            : $this->controller->render($view, $params);
    }

    public function saveResp(bool $ok = true, $ajaxResp = [], $noAjaxResp = null, int $statusCode = 500)
    {
        if (!$ok) {
            throw new HttpException($statusCode);
        }

        if (Yii::$app->request->isAjax) {
            if (is_array($ajaxResp) && isset($ajaxResp[0])) {
                return $this->controller->redirect($ajaxResp);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $ajaxResp['raw'] ?? ArrayHelper::merge(['saved' => true, 'close_modal' => true], $ajaxResp);
        }

        if (is_array($noAjaxResp) && isset($noAjaxResp[0])) {
            return $this->controller->redirect($noAjaxResp);
        }

        if ($noAjaxResp) {
            return $noAjaxResp;
        }

        if (is_array($ajaxResp) && isset($ajaxResp[0])) {
            return $this->controller->redirect($ajaxResp);
        }

        return $ajaxResp;
    }
}