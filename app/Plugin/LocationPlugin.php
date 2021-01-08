<?php


namespace App\Plugin;


use App\Services\AMapService;
use Psr\Container\ContainerInterface;
use Shrimp\Event\ResponseEvent;

class LocationPlugin
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;
    /**
     * @var mixed|config
     */
    private $config;

    /**
     * LocationPlugin constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get('config');
    }

    /**
     * @param ResponseEvent $responseEvent
     */
    public function __invoke(ResponseEvent $responseEvent)
    {
        $lng = (string) $responseEvent->getAttribute("Location_Y");
        $lat = (string) $responseEvent->getAttribute("Location_X");
        $aMap = new AMapService($this->container);
        $response = $aMap->poi([
          'category' => 150700,
          'latitude' => $lat,
          'longitude' => $lng,
          'pagenum' => 1 ,
          'pagesize' => 4,
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
            return ;
        }
        $message = "";
        $incr = 1;
        $appId = $this->config['miniapp']['appid'];
        foreach ($body->poi_list as $key => $item) {
            if ($incr > 7) {
                break;
            }
            $message .= "🚉." . str_replace("(公交站)", "", $item->name)  ." 距离(". (int) $item->distance . ")米\n\n";
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
                $message .= $this->formatLineStrig(
                    $appId,
                    $lineIdArray[$i],
                    $lat,
                    $lng,
                    $line,
                    $stationIdArray[$i]
                );
                $incr++;
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
    private function formatLineStrig($appId, $lineId, $lat, $lng, $name, $stationId)
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
    private function formatOpenMiniapp($appId)
    {
        $str = <<<EOF
🚌.<a data-miniprogram-appid="%s" data-miniprogram-path="pages/index/index" href="/">%s</a>

EOF;
        return sprintf($str, $appId, "打开小程序查看更多");
    }
}
