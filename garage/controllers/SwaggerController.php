<?php

namespace garage\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use function OpenApi\scan;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="TMS Garage API"
 *     )
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="basic",
 *     securityScheme="httpBasic"
 * )
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     securityScheme="main",
 *     @OA\Flow(
 *         flow="password",
 *         tokenUrl="/oauth2/token",
 *         refreshUrl="/oauth2/refresh",
 *         scopes={
 *         }
 *     )
 * )
 */
class SwaggerController extends Controller
{
    //public $enableCsrfValidation = false;

    public function actionDoc()
    {
        $directories = [
            Yii::getAlias('@garage/controllers'),
            Yii::getAlias('@garage/forms'),
            Yii::getAlias('@garage/templates'),
        ];
        $openApi = scan($directories);
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        Yii::$app->getResponse()->content = $openApi->toJson();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}