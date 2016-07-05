<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/5
 * Time: 10:52
 */

namespace App\Lib;


class Baidu
{
    protected $gateway = 'http://api.map.baidu.com/';
    
    protected $ak = null;

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

    public function __construct($ak)
    {
        $this->ak = $ak;
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
            'ak' => $this->ak,
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
}