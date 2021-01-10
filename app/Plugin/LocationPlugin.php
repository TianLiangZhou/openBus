<?php


namespace App\Plugin;


use App\Services\AMapService;
use Shrimp\Event\ResponseEvent;

class LocationPlugin extends Plugin
{
    /**
     * @param ResponseEvent $responseEvent
     */
    public function __invoke(ResponseEvent $responseEvent)
    {
        $appId = $this->config['miniapp']['appid'];
        $lng = (string) $responseEvent->getAttribute("Location_Y");
        $lat = (string) $responseEvent->getAttribute("Location_X");
        if (empty($lng) || empty($lat)) {
            return ;
        }
        $aMap = new AMapService($this->container);
        if ($this->container->has('cache')) {
            /**
             * @var $redis \Redis
             */
            $redis = $this->container->get("cache");
            $openId = (string) $responseEvent->getAttribute("FromUserName");
            $mData = [
                "point" => "$lng,$lat",
            ];
            $regeoResponse = $aMap->geocodeRegeo("$lng,$lat");
            if ($regeoResponse->getStatusCode() == 200) {
                $regeo = json_decode($regeoResponse->getBody()->getContents());
                if ($regeo->status == "1") {
                    $mData['adcode'] = $regeo->regeocode->addressComponent->adcode;
                    $mData['citycode'] = $regeo->regeocode->addressComponent->citycode;
                }
            }
            $redis->hMSet($openId, $mData);
        }
        $response = $aMap->poi([
          'category' => 150700,
          'latitude' => $lat,
          'longitude' => $lng,
          'pagenum' => 1 ,
          'pagesize' => 3,
          'query_type' => "RQBXY",
          'range' => 1000,
          'scenario' => 2,
          'sort_rule' => 1
        ]);
        if ($response->getStatusCode() !== 200) {
            return ;
        }
        $body = json_decode($response->getBody()->getContents());
        if ($body->code != "1" || (int)$body->total < 1) {
            $responseEvent->setResponse(
                "附近没有公交站信息\n\n" . $this->formatOpenMiniapp($appId)
            );
            return ;
        }
        $message = "";
        $incr = 1;
        foreach ($body->poi_list as $key => $item) {
            $message .= "🚉." . str_replace("(公交站)", "", $item->name)  ." 距离". (int) $item->distance . "米\n";
            if ($incr < 7) {
                $splitId = explode("|", $item->stations->businfo_lineids);
                $lineIdArray = [];
                $index = 0;
                foreach ($splitId as $k => $idStr) {
                    $l = explode(";", $idStr);
                    if (count($l) > count($lineIdArray)) {
                        $lineIdArray = $l;
                        $index = $k;
                    }
                }
                $stationIdArray = explode(";", explode("|", $item->stations->businfo_stationids)[$index]);
                $lines = explode(";", explode("|", $item->stations->businfo_line_keys)[$index]);
                foreach ($lines as $i => $line) {
                    if ($incr > 7) {
                        break;
                    }
                    $message .= $this->formatLineString(
                        $appId,
                        $lineIdArray[$i],
                        $lat,
                        $lng,
                        $line,
                        $stationIdArray[$i]
                    );
                    $incr++;
                }
            }
            $message .= "\n";
        }
        $message .= $this->formatOpenMiniapp($appId);
        $responseEvent->setResponse($message);
    }

    /**
     * @param $appId
     * @param $lineId
     * @param $lat
     * @param $lng
     * @param $name
     * @param $stationId
     * @return string
     */
    private function formatLineString($appId, $lineId, $lat, $lng, $name, $stationId): string
    {
        $str = <<<EOF
🚌.<a data-miniprogram-appid="%s" data-miniprogram-path="pages/line/line?lineid=%s&lat=%s&lng=%s&stationid=%s" href="/">%s</a>

EOF;
        return sprintf($str, $appId, $lineId, $lat, $lng, $stationId, $name);
    }

    /**
     * @param $appId
     * @return string
     */
    private function formatOpenMiniapp($appId): string
    {
        $str = <<<EOF
<a data-miniprogram-appid="%s" data-miniprogram-path="pages/index/index" href="/">%s</a>
EOF;
        return sprintf($str, $appId, "打开小程序查看更多");
    }
}
