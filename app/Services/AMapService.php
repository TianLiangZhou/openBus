<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class AMapService
{
    private ClientInterface $client;

    private const GATEWAY = "https://aisle.amap.com";

    private const REST_GATEWAY = "https://restapi.amap.com";

    private const SECRET = "59f783b90e9cb4aaa352b66da1a8d358";

    private const REST_SECRET = "2c5d4e46c31b259672ace4fb21f02c41";

    private array $commonParams = [
        "appFrom" => "alipay",
        "channel" => "amap7a",
        "key" => self::SECRET,
        "miniappid" => "2018051660134749",
        'version' => '2.13'
    ];

    private array $headers = [
        "user-agent" => "Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/18B92 Ariver/1.1.0 AliApp(AP/10.2.6.6000) Nebula WK RVKType(0) AlipayDefined(nt:WIFI,ws:414|672|3.0) AlipayClient/10.2.6.6000 Language/zh-Hans Region/CN NebulaX/1.0.0",
        "Referer" => "https://2018051660134749.hybrid.alipay-eco.com/2018051660134749/0.2.2009282028.54/index.html#pages/realtimebus-index/realtimebus-index",
    ];
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * AMapService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $this->container->has('client')
            ? $this->container->get('client')
            : new Client();
    }

    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function poi(array $arguments)
    {
        $url = self::GATEWAY . '/ws/mapapi/poi/infolite';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function stationLine(array $arguments)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/search/lines';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }


    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function lineStation(array $arguments)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/linestation';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }


    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function line(array $arguments)
    {
        $url = self::GATEWAY . '/ws/mapapi/poi/newbus';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function lineEx(array $arguments)
    {

        $url = self::GATEWAY . '/ws/mapapi/realtimebus/lines/ex/';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param array $arguments
     * @return ResponseInterface
     */
    public function nearLine(array $arguments)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/search/nearby_lines';

        $body = array_merge($this->commonParams, $arguments);

        return $this->getResponse('POST', $url, $body);
    }

    /**
     *
     *
     * @param array $arguments
     * @return ResponseInterface
     */
    public function poiLite(array $arguments)
    {
        $parameters = array_merge($this->commonParams, $arguments);
        $url = self::GATEWAY . '/ws/mapapi/poi/tipslite?' . http_build_query($parameters);
        return $this->getResponse('GET', $url, [], [
            "alipayMiniMark" => "tHG0wINOwEjvrChizNz3Lzw7tzf/qT2HFiidE9IvGU4Wf7qewG2MgBa6gPOxj8TcnLQeDbiXy1GQhbO9f+pAEhZHql2AWxZxfbWPoxRPkcs=",
        ]);
    }

    /**
     * @param string $ip
     * @return ResponseInterface
     */
    public function locationByIp(string $ip = "")
    {
        $url = self::GATEWAY . sprintf('/v3/ip?key=%s&ip=%s', self::SECRET, $ip);
        return $this->getResponse('GET', $url);
    }

    /**
     * @param string $address
     * @return ResponseInterface
     */
    public function locationByAddress(string $address = "")
    {
        $url = self::GATEWAY . sprintf(
                '/v3/geocode/geo?key=%s&address=%s',
                self::SECRET,
                $address
            );
        return $this->getResponse('GET', $url);
    }

    /**
     * @param string $point
     * @return ResponseInterface
     */
    public function geocodeRegeo(string $point)
    {
        $url = self::REST_GATEWAY . sprintf(
                '/v3/geocode/regeo?key=%s&location=%s&extensions=base&poitype=',
                self::REST_SECRET,
                $point
            );
        return $this->getResponse('GET', $url);
    }

    /**
     * @param array $parameters
     * @return ResponseInterface
     */
    public function keywordSearch(array $parameters)
    {
        $url = self::REST_GATEWAY . sprintf(
                    '/v3/place/text?key=%s&%s',
                self::REST_SECRET,
                http_build_query($parameters)
            );
        return $this->getResponse("GET", $url);
    }

    /**
     * @param array $parameters
     * @return ResponseInterface
     */
    public function lineNameSearch(array $parameters)
    {
        $url = self::REST_GATEWAY . sprintf(
            '/v3/bus/linename?key=%s&%s',
                self::REST_SECRET,
                http_build_query($parameters)
            );
        return $this->getResponse("GET", $url);
    }

    /**
     * @param array $parameters
     * @return ResponseInterface
     */
    public function transitIntegrated(array $parameters)
    {
        $url = self::REST_GATEWAY . sprintf(
                '/v3/direction/transit/integrated?key=%s&%s',
                self::REST_SECRET,
                http_build_query($parameters)
            );
        return $this->getResponse("GET", $url);
    }

    /**
     * @param string $version
     * @return ResponseInterface
     */
    public function city(string $version = '202011295')
    {
        return $this->getResponse('GET', 'https://www.amap.com/service/cityList?version=' . $version);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return ResponseInterface
     */
    private function getResponse(string $method, string $url, array $body = [], array $headers = [])
    {
        $options = [
            'headers' => $this->headers
        ];
        if ($body) {
            $options['form_params'] = $body;
        }
        if ($headers) {
            $options['headers'] = array_merge($options['headers'], $headers);
        }
        try {
            return $this->client->request($method, $url, $options);
        } catch (GuzzleException $e) {
            $this->container && $this->container->get('logger')->error($e->getTraceAsString());
        }
        return new JsonResponse([]);
    }
}
