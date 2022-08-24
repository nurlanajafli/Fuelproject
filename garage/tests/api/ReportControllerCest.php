<?php

namespace garage\tests\api;

use Codeception\Util\HttpCode;
use common\enums\DefLevel;
use common\enums\FuelLevel;
use common\enums\ReportType;
use common\enums\ReportFlag;
use common\models\Trailer;
use common\models\Driver;
use common\models\Report;
use common\models\Truck;
use garage\tests\ApiTester;
use yii\helpers\Json;

class ReportControllerCest
{
    public function _before(ApiTester $I)
    {
        $I->authorize();
    }

    public function actionShow(ApiTester $I)
    {
        $I->sendGet('/report/0');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $report = $I->have(Report::class);
        $I->sendGet('/report/' . $report->id);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 'success', 'data' => ['id' => $report->id]]);
        // or
        // $I->assertArrayHasKey('data', Json::decode($I->grabResponse()));
    }

    public function actionSign(ApiTester $I) {
//        $I->sendPost('/report/0/sign');
//        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
//        $report = Report::find()->one();
        $report = $I->have(Report::class);
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->sendPost('/report/'.$report->id.'/sign',[
            'ReportSign' => [
                'file' =>  codecept_data_dir('image.jpg'),
                'flags' => [ReportFlag::PRE_PASS,ReportFlag::BAS_PASS],
            ]
        ],
            [
                'ReportSign' => [
                    'file' =>  codecept_data_dir('image.jpg')
                ]
            ]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 'success']);
    }

    public function actionCreate(ApiTester $I) {
        $truck = Truck::find()->one();
        $trailer = Trailer::find()->one();
        $driver = Driver::find()->one();
        $I->sendPost('/report',[
            'ReportCreate' => [
                'truckId' => $truck->id,
                'trailerId' => $trailer->id,
                'type' => ReportType::CHECK_OUT,
                'driverId' => $driver->id,
                'dateTime' => '2021-12-18T11:15:47-0800',
                'mileage' => '200',
                'defLevel' => DefLevel::HALF,
                'fuelLevel' => FuelLevel::ONE_QUARTER,
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 'success', 'data' => ['status'=>'Draft']]);
    }

    function actionIndex(ApiTester $I) {
        $I->sendGet('/report',[
            'truckId' => '0',
            'trailerId' =>  '0',
            'page'=> '0',
            'pageSize' => '1'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 'success']);
        //$I->seeResponseContainsJson(['status' => 'success', 'data' => ['status'=>'Draft']]);
    }

}
