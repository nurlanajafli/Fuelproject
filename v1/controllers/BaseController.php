<?php

namespace v1\controllers;

use common\controllers\traits\AllowedAttributes;
use common\controllers\traits\RequiredAttributes;
use common\enums\Scope;
use Exception;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use v1\components\AccessRule;
use v1\components\HttpException;
use v1\components\oauth2\filters\CompositeAuth;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

abstract class BaseController extends Controller
{
    use AllowedAttributes;
    use RequiredAttributes;

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'roles' => ['@'],
                'scopes' => [Scope::DRIVER]
            ]
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $contentNegotiator = $behaviors['contentNegotiator'];
        unset($behaviors['contentNegotiator']);
        $contentNegotiator['formats'] = ['application/json' => Response::FORMAT_JSON];
        $behaviors = ArrayHelper::merge(
            $behaviors,
            [
                'contentNegotiator' => $contentNegotiator,
                'authenticator' => [
                    'class' => CompositeAuth::class,
                    'authMethods' => [
                        ['class' => HttpBearerAuth::class],
                    ]
                ],
                'exceptionFilter' => [
                    'class' => ErrorToExceptionFilter::class
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => $this->accessRules(),
                    'ruleConfig' => ['class' => AccessRule::class]
                ]
            ]
        );
        return $behaviors;
    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);

        if ($result && !$this->isHasAllRequiredAttributes()) {
            throw new BadRequestHttpException(Yii::t('app', 'Wrong params set'));
        }

        if ($result) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if (YII_DEBUG) {
            \yii::getLogger()->log(\yii::$app->getRequest()->getMethod() . " {$this->id}/{$action->id}", Logger::LEVEL_INFO, 'request-trace');
        }

        return $result;
    }

    protected function saveModel(ActiveRecord $model, bool $refreshAfterSave = true)
    {
        $proxy = false;
        try {
            if (!$model->save()) {
                $proxy = true;
                throw new HttpException(400, [$model->formName() => $model->getErrors()]);
            }
        } catch (Exception $exception) {
            if ($proxy) {
                throw $exception;
            }
            throw new ServerErrorHttpException();
        }
        if ($refreshAfterSave) {
            $model->refresh();
        }
    }

    protected function index(ActiveQuery $q, int $page, int $pageSize, string $template, array $templateConfig = [])
    {
        $pagination = new Pagination(['page' => $page, 'pageSize' => $pageSize, 'totalCount' => $q->count()]);
        $rows = $q->limit($pagination->getLimit())->offset($pagination->getOffset())->all();
        $data = [];
        foreach ($rows as $row) {
            array_push($data, $row->getAsArray($template, $templateConfig));
        }
        return $this->success($data, [
            'pagination' => [
                'page' => $pagination->getPage(),
                'page_size' => $pagination->getPageSize(),
                'page_count' => $pagination->getPageCount(),
                'total_count' => $pagination->totalCount
            ]
        ]);
    }

    protected function success($data = null, $meta = null)
    {
        return [
            'status' => 'success',
            'data' => $data,
            'meta' => $meta
        ];
    }
}

/**
 *
 * @OA\Schema(
 *     schema="Pagination",
 *     @OA\Property(
 *         property="pagination",
 *         type="object",
 *         @OA\Property(
 *             property="page",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="page_size",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="page_count",
 *             type="integer"
 *         ),
 *         @OA\Property(
 *             property="total_count",
 *             type="integer"
 *         )
 *     )
 * )
 *
 */