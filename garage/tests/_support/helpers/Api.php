<?php

namespace garage\tests\helpers;

use Codeception\Util\HttpCode;
use yii\helpers\Json;

class Api extends \Codeception\Module
{
    public function seeResponseCodeIs(int $code)
    {
        $REST = $this->getModule('REST');
        if ($code == HttpCode::OK) {
            $REST->seeResponseCodeIs($code);
        } else {
            $REST->seeResponseIsJson();
            $resp = Json::decode($REST->grabResponse());
            $this->assertArrayHasKey('code', $resp);
            $this->assertEquals($code, $resp['code']);
        }
    }
}
