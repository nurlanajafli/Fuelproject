<?php

namespace v1\controllers;

use filsh\yii2\oauth2server\Module;
use filsh\yii2\oauth2server\Request;
use OAuth2\ResponseInterface;
use Yii;
use yii\rest\Controller;

/**
 * @OA\Post(
 *     path="/oauth2/token",
 *     summary="Access token",
 *     tags={"oauth2"},
 *     operationId="accessToken",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="grant_type",
 *                 type="string",
 *                 example="password"
 *             ),
 *             @OA\Property(
 *                 property="username",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successfull operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="access_token",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="expires_in",
 *                 type="integer",
 *                 example=86400
 *             ),
 *             @OA\Property(
 *                 property="token_type",
 *                 type="string",
 *                 example="bearer"
 *             ),
 *             @OA\Property(
 *                 property="scope",
 *                 type="string",
 *                 example="driver"
 *             ),
 *             @OA\Property(
 *                 property="refresh_token",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     security={
 *         {"httpBasic":{}}
 *     }
 * )
 * @OA\Post(
 *     path="/oauth2/refresh",
 *     summary="Refresh token",
 *     tags={"oauth2"},
 *     operationId="refreshToken",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="grant_type",
 *                 type="string",
 *                 example="refresh_token"
 *             ),
 *             @OA\Property(
 *                 property="refresh_token",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successfull operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="access_token",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="expires_in",
 *                 type="integer",
 *                 example=86400
 *             ),
 *             @OA\Property(
 *                 property="token_type",
 *                 type="string",
 *                 example="bearer"
 *             ),
 *             @OA\Property(
 *                 property="scope",
 *                 type="string",
 *                 example="driver"
 *             )
 *         )
 *     ),
 *     security={
 *         {"httpBasic":{}}
 *     }
 * )
 */
class RestController extends Controller
{
    public function actionRefresh()
    {
        return $this->actionToken();
    }

    public function actionToken()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('oauth2');
        $request = Request::createFromGlobals();
        /** @var ResponseInterface $response */
        $response = $module->getServer()->handleTokenRequest($request);
        Yii::$app->end(0, $response);
    }

    public function actionRevoke()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('oauth2');
        $response = $module->getServer()->handleRevokeRequest();
        return $response->getParameters();
    }
}