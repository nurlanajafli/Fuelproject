<?php

namespace common\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

abstract class MapsController extends BaseController
{
    public function afterAction($action, $result)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::afterAction($action, $result);
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;
        /** @var \common\components\PCMiler $pcmiler */
        $pcmiler = Yii::$app->pcmiler;
        $params = [
            'query' => $request->get('query')
        ];
        if ($maxResults = $request->get('maxResults')) {
            $params['maxResults'] = $maxResults + 0;
        }
        if ($currentLonLat = $request->get('currentLonLat')) {
            $params['currentLonLat'] = $currentLonLat;
        }
        if ($maxCleanupMiles = $request->get('maxCleanupMiles')) {
            $params['maxCleanupMiles'] = $maxCleanupMiles;
        }
        if ($countries = $request->get('countries')) {
            $params['countries'] = $countries;
        }
        if ($states = $request->get('states')) {
            $params['states'] = $states;
        }
        if ($postcode = $request->get('postcode')) {
            $params['postcode'] = $postcode;
        }

        if ($city = $request->get('city')) {
            $params['city'] = $city;
        }

        $locations = $pcmiler->search('NA', $params);
        $result = [];
        $resultTemp = [];
        foreach ($locations as $location) {
            $searchLocationsParams = ['list' => 1, 'region' => 4];
            $address = $location->Address;
            $coords = $location->Coords;
            $timeZone = '';
            if ($address->Zip) {
                $searchLocationsParams['postcode'] = $params['postcode'] ? $params['postcode'] : $address->Zip;
            } else {
                $searchLocationsParams['Coords'] = "{$coords->Lon},{$coords->Lat}";
            }

            $searchLocationsParams['street'] = $params['query'];

            if($params['states'])
                $searchLocationsParams['state'] = $params['states'];

            if($params['city'])
                $searchLocationsParams['city'] = $params['city'];

            $resp = $pcmiler->searchLocations($searchLocationsParams);

            if ($resp && is_string($resp[0]->TimeZone) && $resp[0]->TimeZone) {
                $timeZone = $resp[0]->TimeZone;
            }
            array_push($resultTemp, [
                "country_abbreviation" => $address->Country,
                "state_abbreviation" => $address->State,
                "city" => $address->City,
                "zip" => $address->Zip,
                "street_address" => $address->StreetAddress,
                "address" => "{$address->StreetAddress}, {$address->City}, {$address->State}, {$address->Zip}",
                "lat" => $coords->Lat,
                "lon" => $coords->Lon,
                "time_zone" => $timeZone
            ]);
        }

        if($resp) {
            foreach ($resp as $r) {
                array_push($result, [
                    "country_abbreviation" => $r->Address->CountryAbbreviation,
                    "state_abbreviation" => $r->Address->StateAbbreviation,
                    "city" => $r->Address->City,
                    "zip" => $r->Address->Zip,
                    "street_address" => $r->Address->StreetAddress,
                    "address" => "{$r->Address->StreetAddress}, {$r->Address->City}, {$r->Address->StateAbbreviation}, {$r->Address->Zip}",
                    "lat" => $r->Coords->Lat,
                    "lon" => $r->Coords->Lon,
                    "time_zone" => $r->TimeZone,
                ]);
            }
        }
        $result = ArrayHelper::merge($result,$resultTemp);
        return $result;
    }
}	
