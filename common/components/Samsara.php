<?php

namespace common\components;

use yii\base\Component;
use yii\httpclient\Client;

class Samsara extends Component
{
    public $apiKey;
    public $baseUrl = 'https://api.samsara.com';

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @return \yii\httpclient\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function request($method, $url, $params = [])
    {
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'requestConfig' => ['format' => Client::FORMAT_JSON],
            'responseConfig' => ['format' => Client::FORMAT_JSON]
        ]);
        return $client->createRequest()
            ->setHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
            ->setMethod($method)
            ->setUrl($url)
            ->setData($params)
            ->send();
    }
}