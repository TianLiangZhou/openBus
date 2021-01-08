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
     * LocationPlugin constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        foreach ($body->poi_list as $key => $item) {
            $message .= "🚉." . $item->name  ." 距离(". (int) $item->distance . ")米\n";
            $lines = explode(";", $item->stations->businfo_line_names);
            foreach ($lines as $line) {
                $message .= "🚌." . $line."\n";
            }
            $message .= "\n";
        }
        $responseEvent->setResponse($message);
    }
}
