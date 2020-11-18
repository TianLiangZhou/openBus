<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/5
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Lib;

use App\Exception\LineException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;

class Baidu
{
    private string $gateway = 'https://api.map.baidu.com/';
    
    private ?string $secret;

    /**
     * @var array
     * driving（驾车）、walking（步行）、transit（公交）、riding（骑行）
     */
    protected array $lineMode = [
        1 => 'driving',
        2 => 'walking',
        3 => 'transit',
        4 => 'riding'
    ];

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(string $secret)
    {
        $this->secret = $secret;

        $this->client = new Client([
            'timeout' => 3.0,
        ]);
    }

    /**
     * @param $start
     * @param $end
     * @param string $region
     * @param int $mode
     * @param bool $allRoutes
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws LineException
     */
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
        $response = $this->client->request('GET', $this->gateway . 'direction/v1', ['query' => $params]);
        if ($response->getStatusCode() !== 200) {
            throw new LineException("获取线路信息失败!");
        }
        
        $line = json_decode($response->getBody()->getContents(), true);
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


    /**
     * @param $line
     * @param string $region
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws LineException
     */
    public function getBusLine($line, string $region = '杭州市')
    {
        $url = $this->gateway
            . '?qt=s&c=1&wd='. urlencode($region) .'&rn=10&log=center&ie=utf-8&oue=1&ak=' . $this->secret;
        $response = $this->client->request( 'GET', $url, []);
        if ($response->getStatusCode() !== 200) {
            throw new LineException("获取城市信息失败，请输入正确的城市");
        }
        $city = json_decode($response->getBody()->getContents(), true);
        if (!isset($city['current_city']['code'])) {
            throw new LineException("获取城市信息失败，请输入正确的城市");
        }
        $code = $city['current_city']['code'];
        $response = $this->client->request(
            'GET',
            $this->gateway . '?qt=bl&c='. $code .'&wd='. urlencode($line) .'&ie=utf-8&oue=1&ak=' . $this->secret,
            []
        );
        if ($response->getStatusCode() !== 200) {
            throw new LineException("公交线路信息查询失败，可能该城市无此线路");
        }
        $line = json_decode($response->getBody()->getContents(), true);
        if (empty($line['content'][0]) || empty($line['content'][0]['uid'])) {
            throw new LineException("公交线路信息查询失败，可能该城市无此线路");
        }
        $lines = $line['content'];
        $promises = [];
        $uid = [];
        foreach ($lines as $key => $item) {
            if ($key > 1) {
                break;
            }
            $url = $this->gateway . '?qt=bsl&c='. $code .'&uid='. $item['uid'] .'&ie=utf-8&oue=1&ak=' . $this->secret;
            $promises[$item['uid']] = $this->client->getAsync($url);
            $uid[] = $item['uid'];
        }
        $responses = Utils::settle($promises)->wait();
        $detail = [];
        foreach ($uid as $u) {
            $content = json_decode($responses[$u]['value']->getBody()->getContents(), true);
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
