<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use common\enums\Permission;
use common\models\User;
use common\widgets\DataTables\Grid;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class PermissionController extends base\BaseController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'update' => ['post']
                ]
            ]
        ]);
    }

    public function actionIndex($userId = null)
    {
        if (Yii::$app->request->isAjax) {
            $auth = Yii::$app->authManager;
            $categories = [
                'System Administrator' => 1,
                'System Setup' => 5,
                'Lists Setup' => 16,
                'Work Center - My Work' => 4,
                'Work Center - Driver Mail' => 2,
                'Work Center - Web Mail' => 5,
                'Dashboard' => 2,
                'Reporter' => 2,
                'Reporter Data' => 7,
                'Dispatch Manager' => 7,
                'Dispatch Boards' => 7,
                'Dispatch Tools' => 12,
                'Dispatch Settings' => 11,
                'Accounting Manager' => 14,
                'Accounting Settings' => 4,
                'Accounting Reports' => 8,
                'Accounting Tools' => 7,
                'Receivables' => 9,
                'Payables' => 7,
                'Banking' => 12,
                'Payroll' => 17,
                'Fleet Management' => 8,
                'Imaging' => 30,
                'Advanced' => 1,
                'Custom' => 1,
            ];
            $permissions = [];
            $array = Permission::getEnums();
            $catKeys = array_keys($categories);
            $i = $j = 0;
            foreach ($array as $value) {
                $permissions[] = ['category' => $catKeys[$i], 'description' => $value, 'id' => $value, 'can' => $auth->checkAccess($userId, $value)];
                $j++;
                if ($j == $categories[$catKeys[$i]]) {
                    $i++;
                    $j = 0;
                }
            }
            $grid = new Grid([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $permissions,
                    'pagination' => false,
                ]),
                'columns' => [
                    'category',
                    'description',
                    'id',
                    'can',
                ]
            ]);
            return $this->replyJson($grid->getData());
        }

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => User::find()->andWhere(['status' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE]]),
                'pagination' => false
            ])
        ]);
    }

    public function actionUpdate()
    {
        $form = new PermissionForm();
        $form->load(Yii::$app->request->post());
        $status = ($form->validate() &&
            Yii::$app->transaction->exec(function () use ($form) {
                $auth = Yii::$app->authManager;
                if ($auth->getAssignments($form->userId) && !$auth->revokeAll($form->userId)) {
                    return false;
                }
                foreach ($form->permissions as $name) {
                    $permission = $auth->getPermission($name);
                    if (!$permission) {
                        return false;
                    }
                    $auth->assign($permission, $form->userId);
                }
                return true;
            })
        ) ? 'ok' : 'fail';
        return $this->replyJson(['status' => $status]);
    }
}