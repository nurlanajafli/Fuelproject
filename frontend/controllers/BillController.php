<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\BillStatus;
use common\enums\BillStatusFilter;
use common\enums\PaymentType;
use common\enums\Permission;
use common\models\Bill;
use common\models\Payment;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use frontend\forms\bill\AdjustBalance;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class BillController extends base\BaseController
{
    public function allowedAttributes()
    {
        return [
            'create' => [
                (new Bill())->formName() => [
                    'from_carrier_id', 'from_vendor_id', 'bill_no', 'posting_date', 'bill_date', 'ap_account', 'payment_terms', 'due_date', 'office_id', 'memo'
                ]
            ],
            'update' => [
                (new Bill())->formName() => [
                    'from_carrier_id', 'from_vendor_id', 'bill_no', 'posting_date', 'bill_date', 'ap_account', 'payment_terms', 'due_date', 'office_id', 'memo'
                ]
            ]
        ];
    }

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'data', 'update'],
                'permissions' => [Permission::VIEW_BILLS]
            ],
            [
                'allow' => true,
                'actions' => ['index','data','update','delete','create','adjust-balance','data'],
                'permissions' => [Permission::ADD_EDIT_BILLS]
            ],
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return true;
                },
                'form' => function ($bool) {
                    return new Bill(['status' => BillStatus::OPEN]);
                },
                'save' => function (Bill $form, bool $model, string $buttonCode) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form));
                },
            ],
            'update' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return Bill::findOne($actionParams['id']);
                },
                'form' => 'proxy',
                'save' => function ($form, $model, $buttonCode) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form));
                },
            ],
            'adjust-balance' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return Bill::findOne($actionParams['id']);
                },
                'form' => function (Bill $model) {
                    return new AdjustBalance();
                },
                'viewParams' => function (Bill $model) {
                    return ['bill' => $model];
                },
                'save' => function (AdjustBalance $form, Bill $model, string $buttonCode) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $payment = new Payment();
                    $payment->type = PaymentType::BILL_ADJ;
                    $payment->bill_id = $model->id;
                    $payment->date = $form->date;
                    $payment->account = $form->glAccount;
                    $payment->amount = $form->amount;
                    $payment->office_id = $form->office;
                    $payment->our_ref = $form->ourRef;
                    $payment->udf = $form->udf;
                    $payment->memo = $form->memo;
                    $this->saveModel($payment);
                    return '
                      <script type="text/javascript">
                        jQuery("#bill-adjustbalance-modal").modal("hide");
                      </script>
                    ';
                },
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionData($officeId = null, $account = null, $status = null, $carrierId = null, $vendorId = null, $dateFrom = null, $dateTo = null)
    {
        $expression = '1=1';
        $params = [];
        if ($officeId) {
            $expression .= ' AND office_id=:office_id';
            $params[':office_id'] = $officeId;
        }
        if ($account) {
            $expression .= ' AND ap_account=:ap_account';
            $params[':ap_account'] = $account;
        }
        if ($status && $status != BillStatusFilter::ALL) {
            switch ($status) {
                case BillStatusFilter::PAID:
                    $expression .= ' AND status=:status';
                    $params[':status'] = BillStatus::PAID;
                    break;
                case BillStatusFilter::OPEN_ALL:
                    $expression .= ' AND status=:status';
                    $params[':status'] = BillStatus::OPEN;
                    break;
                case BillStatusFilter::OPEN_BY_DATE:
                    $expression .= ' AND status=:status';
                    $params[':status'] = BillStatus::OPEN;
                    if ($dateFrom) {
                        $expression .= ' AND >=:date_from';
                        $params[':date_from'] = $dateFrom;
                    }
                    break;
                case BillStatusFilter::SHORT_PAID:
                    $expression .= ' AND status=:status';
                    $params[':status'] = BillStatus::SHORT_PAID;
                    break;
                case BillStatusFilter::PAST_DUE:
                    $expression .= ' AND status=:status';
                    $params[':status'] = BillStatus::PAST_DUE;
                    break;
            }
        }
        if ($carrierId) {
            $expression .= ' AND from_carrier_id=:from_carrier_id';
            $params[':from_carrier_id'] = $carrierId;
        }
        if ($vendorId) {
            $expression .= ' AND from_vendor_id=:from_vendor_id';
            $params[':from_vendor_id'] = $vendorId;
        }
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => Bill::find()->where(new Expression($expression, $params)),
                'pagination' => false,
            ]),
            'columns' => [
                'id',
                new DataColumn([
                    'value' => function ($model) {
                        return '<i class="fas fa-plus"></i><i class="fas fa-minus"></i>';
                    },
                ]),
                'bill_no',
                'bill_date',
                'due_date',
                new DataColumn([
                    'value' => function (Bill $model) {
                        if ($model->fromCarrier) {
                            return $model->fromCarrier->name;
                        }
                        if ($model->fromVendor) {
                            return $model->fromVendor->name;
                        }
                        return '';
                    },
                ]),
                'office_id|rel=office.office',
                'ap_account',
                'amount|decimal',
                'balance|decimal',
                'memo'
            ]
        ]);
        return $this->replyJson($grid->getData());
    }

    public function actionDelete($id)
    {
        /** @var Bill $model */
        $model = $this->findModel($id);
        if (!$model->canDelete()) {
            throw new BadRequestHttpException();
        }

        try {
            $model->delete();
        } catch (\Exception $exception) {
            throw new BadRequestHttpException();
        } catch (\Throwable $e) {
            throw new BadRequestHttpException();
        }
        return $this->replyJson(['message' => 'Success']);
    }

    protected function findModel($id, $status = null)
    {
        $condition = ['id' => $id];
        if ($status) {
            $condition['status'] = $status;
        }
        $model = Bill::findOne($condition);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
