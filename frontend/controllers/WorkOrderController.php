<?php

namespace frontend\controllers;

use Closure;
use common\actions\ChildFormProcessingAction;
use common\actions\FormProcessingAction;
use common\enums\WorkOrderStatus;
use common\enums\WorkOrderType;
use common\models\User;
use common\models\WorkOrder as Order;
use common\models\WorkOrderService as OrderService;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class WorkOrderController extends base\BaseController
{
    protected function accessRules()
    {
        return [];
    }

    protected function allowedAttributes()
    {
        return [
            'update' => [
                (new Order())->formName() => [
                    'order_date',
                    'order_type',
                    'truck_id',
                    'trailer_id',
                    'vendor_id',
                    'authorized_by',
                    'odometer',
                    'description',
                ]
            ],
            'update-service' => [
                (new OrderService())->formName() => ['service_date', 'service_code', 'vendor_id','description']
            ],
        ];
    }

    protected function findModel($id, $status)
    {
        return Order::findOne(['id' => $id, 'status' => $status]);
    }

    protected function field($form, $model, $attribute)
    {
        return $form->field($model, $attribute, [
            'options' => ['tag' => false],
            'template' => sprintf(
                '<label class="setting__fields--label"><p>%s</p>{input}{error}</label>',
                $model->getAttributeLabel($attribute)
            )
        ]);
    }

    public function actions()
    {
        $fpa = FormProcessingAction::class;
        return [
            'update' => [
                'class' => $fpa,
                'before' => function ($actionParams) {
                    /** @var User $user */
                    $user = Yii::$app->user->identity;
                    if ($actionParams['id']) {
                        $model = Order::findOne(['id' => $actionParams['id'], 'status' => [WorkOrderStatus::OPEN, WorkOrderStatus::COMPLETED]]);
                        return $model ?: $user->getWorkOrders()->where(['id' => $actionParams['id'], 'status' => WorkOrderStatus::DRAFT])->one();
                    }

                    $model = $user->getWorkOrders()->where(['status' => WorkOrderStatus::DRAFT])->one();
                    if ($model) {
                        return $model;
                    }

                    $model = new Order();
                    $model->status = WorkOrderStatus::DRAFT;
                    $model->order_date = Yii::$app->formatter->asDate('now', 'y-MM-dd');
                    $model->order_type = WorkOrderType::TRUCK;
                    $this->saveOrFail($model);
                    return $model;
                },
                'form' => 'proxy',
                'viewParams' => ['field' => Closure::fromCallable([$this, 'field'])],
                'save' => function (Order $form, Order $model, string $submitButtonCode) {
                    if ($form->status == WorkOrderStatus::DRAFT) {
                        $form->status = WorkOrderStatus::OPEN;
                    }
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($form->save());
                },
            ],
            'update-service' => [
                'class' => ChildFormProcessingAction::class,
                'before' => function ($actionParams) {
                    return $actionParams['id'] ?
                        OrderService::findOne(['order_id' => $actionParams['parentId'], 'id' => $actionParams['id']]) :
                        new OrderService(['order_id' => $actionParams['parentId'], 'service_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')]);
                },
                'form' => 'proxy',
                'viewParams' => ['field' => Closure::fromCallable([$this, 'field'])],
                'save' => function (OrderService $form, OrderService $model, string $button) {
                    /** @var ChildFormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($form->save());
                }
            ]
        ];
    }

    public function actionIndex($data = 0)
    {
        if ($data) {
            $query = Order::find()->where(['status' => [WorkOrderStatus::OPEN, WorkOrderStatus::COMPLETED]])->orderBy(['id' => SORT_ASC]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false,
            ]);
            $grid = new Grid([
                'dataProvider' => $dataProvider,
                'columns' => [
                    new DataColumn([
                        'value' => function ($model) {
                            return '<button class="btn btn-link js-ajax-modal" data-url="' . Url::toRoute(['update', 'id' => $model->id]) . '">' . $model->id . '</button>';
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return '';
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->order_date, 'MM/dd/yy');
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return '';
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return '';
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return '';
                        },
                    ]),
                    new DataColumn([
                        'value' => function ($model) {
                            return '';
                        },
                    ]),
                    'vendor_id|rel=vendor.name|title=Serviced By',
                    'description|title=Description'
                ]
            ]);
            return $this->replyJson($grid->getData());
        }

        return $this->render('index');
    }

    public function actionIndexService($id)
    {
        $query = OrderService::find()->where(['order_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $grid = new Grid([
            'dataProvider' => $dataProvider,
            'columns' => [
                new DataColumn([
                    'value' => function ($model) {
                        return '<button class="btn btn-link js-ajax-modal" data-url="' . Url::toRoute(['update-service', 'parentId' => $model->order_id, 'id' => $model->id]) . '">' . $model->id . '</button>';
                    },
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        return '';
                    },
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        return '';
                    },
                ]),
                'description',
                new DataColumn([
                    'value' => function ($model) {
                        return '';
                    },
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        return '';
                    },
                ]),
                new DataColumn([
                    'value' => function ($model) {
                        return '';
                    },
                ]),
            ]
        ]);
        return $this->replyJson($grid->getData());
    }
}
