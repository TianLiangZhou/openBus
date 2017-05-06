<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/5
 * Time: 10:52
 */

namespace App\Lib;


use GuzzleHttp\Promise\Promise;

class Baidu
{
    protected $gateway = 'http://api.map.baidu.com/';
    
    protected $secret = null;

    /**
     * @var array
     * driving（驾车）、walking（步行）、transit（公交）、riding（骑行）
     */
    protected $lineMode = [
        1 => 'driving', 
        2 => 'walking',
        3 => 'transit',
        4 => 'riding'
    ];

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function getLineInfo($start, $end, $region = '杭州', $mode = 3, $allRoutes = false)
    {
        //TODO http://lbsyun.baidu.com/index.php?title=webapi/direction-api
        $params = [
            'mode' => $this->lineMode[$mode],
            'origin' => $start,
            'destination' => $end,
            'region' => $region,
            'output' => 'json',
            'ak' => $this->secret,
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->gateway . 'direction/v1?' . http_build_query($params));
        
        $line = json_decode($response->getBody(), true);
        $bestLine = [];
        if ($line['status'] !== 0 && $line['message'] !== 'ok') {
            return $bestLine;
        }
        if (!isset($line['result']['routes'][0])) {
            return $bestLine;
        }
        if ($allRoutes) {
            return $line['result']['routes'];
        }
        foreach ($line['result']['routes'][0]['scheme'][0]['steps'] as $key => $value) {
            if (empty($value[0]['vehicle'])) {
                $bestLine[$key] = $value[0]['stepInstruction'];
            } else {
                $bestLine[$key] = $value[0]['vehicle'];
            }
        }
        return $bestLine;
    }


    public function getBusLine($line, $region = '杭州')
    {
        $client = new \GuzzleHttp\Client([
            'timeout' => 3.0
        ]);
        $response = $client->request(
            'GET', $this->gateway
                . '?qt=s&c=1&wd='. $region .'&rn=10&log=center&ie=utf-8&oue=1&ak=' . $this->secret,
            []
        );
        $city = json_decode($response->getBody(), true);
        if (!isset($city['current_city']['code'])) {
            return [];
        }
        $code = $city['current_city']['code'];

        $response = $client->request(
            'GET',
            $this->gateway . '?qt=bl&c='. $code .'&wd='. $line .'&ie=utf-8&oue=1&ak=' . $this->secret,
            []
        );

        $line = json_decode($response->getBody(), true);
        if (empty($line['content'][0])) {
            return [];
        }
        $lines = $line['content'];
        $promises = [];
        $uid = [];
        foreach ($lines as $item) {
            $promises[$item['uid']] = $client->getAsync(
                $this->gateway . '?qt=bsl&c='. $code .'&uid='. $item['uid'] .'&ie=utf-8&oue=1&ak=' . $this->secret
            );
            $uid[] = $item['uid'];
        }
        $responses = \GuzzleHttp\Promise\settle($promises)->wait();

        $detail = [];
        foreach ($uid as $u) {
            $content = json_decode($responses[$u]['value']->getBody(), true);
            if (isset($content['content'][0])) {
                $site = array_map(
                    function($value) { return $value['name'];}, $content['content'][0]['stations']
                );
                $detail[] = [
                    'name' => $content['content'][0]['name'],
                    'time' => $content['content'][0]['timetable'],
                    'price'=> $content['content'][0]['ticketPrice'],
                    'station' => $site,
                ];
            }
        }
        return $detail;
    }
}