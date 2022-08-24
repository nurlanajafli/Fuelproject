<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class PCMiler extends Component
{
    public $apiKey;
    public $cacheDuration;

    protected function request($url, $params, $postBody = null)
    {
        $client = new Client();
        $req = $client->createRequest()->setUrl(ArrayHelper::merge([$url], ['authToken' => $this->apiKey], $params));
        if ($postBody) {
            $req->setMethod('POST')->setFormat(Client::FORMAT_JSON)->setData($postBody);
            $cacheKey = 'pcm_' . md5($req->getFullUrl() . serialize($postBody));
        } else {
            $cacheKey = 'pcm_' . md5($req->getFullUrl());
        }
        $cache = Yii::$app->getCache();
        if ($cacheData = $cache->get($cacheKey)) {
            return $cacheData;
        }
        $resp = $req->send();
        if ($resp->isOk) {
            $format = $resp->getFormat();
            if ($format == Client::FORMAT_JSON) {
                $client->getParser($format)->asArray = false;
            }
            $result = $format ? $resp->getData() : $resp->getContent();
            $cache->delete($cacheKey);
            if (is_string($result) || !ArrayHelper::getValue($result, 'Err')) {
                $cache->set($cacheKey, $result, $this->cacheDuration);
            }
            return $result;
        }
        return null;
    }

    public function search($region, $params)
    {
        $result = $this->request("https://singlesearch.alk.com/$region/api/search", $params);
        return ArrayHelper::getValue($result, 'Locations', []);
    }

    public function searchLocations($params)
    {
        return $this->request("https://pcmiler.alk.com/apis/rest/v1.0/service.svc/locations", $params);
    }

    public function routeReports($points, $params)
    {
        $stops = implode(';', array_map(function ($point) {
            return "{$point['longitude']},{$point['latitude']}";
        }, $points));
        return $this->request("https://pcmiler.alk.com/apis/rest/v1.0/Service.svc/route/routeReports", array_merge($params, ['stops' => $stops]));
    }

    public function getDistance($point1, $point2, $params = [], $default = null)
    {
        if (empty($point1) || empty($point2)) {
            return $default;
        }
        $i = ArrayHelper::getValue($this->routeReports([$point1, $point2], array_merge(['reports' => 'CalcMiles'], $params)), [0, 'TMiles']);
        return is_null($i) ? $default : round($i);
    }

    public function mapRoutes($postBody)
    {
        return $this->request('https://pcmiler.alk.com/apis/rest/v1.0/service.svc/mapRoutes', [], $postBody);
    }
}