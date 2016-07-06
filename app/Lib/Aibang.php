<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/6
 * Time: 21:37
 */

namespace App\Lib;


class Aibang
{
    protected $gateway = 'http://openapi.aibang.com/';

    protected $secret = null;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function getBusLineStatsDetail($lineNumber, $city = '杭州', $point = false)
    {
        $params = [
            'app_key' => $this->secret,
            'q' => $lineNumber,
            'city' => $city,
            'alt' => 'json',
            'with_xys' => 0
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->gateway . 'bus/lines?' . http_build_query($params));
        $detail = json_decode($response->getBody(), true);
        if (!empty($detail['message'])) {
            return [];
        }
        if (empty($detail['result_num'])) {
            return [];
        }
        $line = $detail['lines']['line'];
        foreach ($line as &$value) {
            $value['stats'] = explode(';', $value['stats']);
        }
        return $line;
    }
}