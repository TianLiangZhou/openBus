<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AMapController
 * @package App\Http\Controllers
 */
class AMapController extends BaseController
{
    private ClientInterface $client;

    private const GATEWAY = "https://aisle.amap.com";

    private const SECRET = "59f783b90e9cb4aaa352b66da1a8d358";

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


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->client = new Client();
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function poi(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . '/ws/mapapi/poi/infolite';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function stationLine(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/search/lines';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function lineStation(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/linestation';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function line(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . '/ws/mapapi/poi/newbus';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function lineEx(ServerRequestInterface $request, ResponseInterface $response)
    {

        $url = self::GATEWAY . '/ws/mapapi/realtimebus/lines/ex/';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function nearLine(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . '/ws/mapapi/realtimebus/search/nearby_lines';

        $body = array_merge($this->commonParams, $request->getParsedBody());

        return $this->getResponse('POST', $url, $body);
    }

    /**
     *
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function poiLite(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parameters = array_merge($this->commonParams, $request->getQueryParams());
        $url = self::GATEWAY . '/ws/mapapi/poi/tipslite?' . http_build_query($parameters);
        return $this->getResponse('GET', $url, [], [
            "alipayMiniMark" => "tHG0wINOwEjvrChizNz3Lzw7tzf/qT2HFiidE9IvGU4Wf7qewG2MgBa6gPOxj8TcnLQeDbiXy1GQhbO9f+pAEhZHql2AWxZxfbWPoxRPkcs=",
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function locationByIp(ServerRequestInterface $request, ResponseInterface $response)
    {
        $servers = $request->getServerParams();
        $url = self::GATEWAY . sprintf('/v3/ip?key=%s&ip=%s', self::SECRET, $servers['REMOTE_ADDR']);
        return $this->getResponse('GET', $url);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function locationByAddress(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = self::GATEWAY . sprintf(
            '/v3/geocode/geo?key=%s&address=%s',
            self::SECRET,
                $request->getQueryParams()['address'] ?? ''
            );
        return $this->getResponse('GET', $url);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function city(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->getResponse('GET', 'https://www.amap.com/service/cityList?version=202011295');
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
            $this->container->get('logger')->error($e->getTraceAsString());
        }
        return new JsonResponse([]);
    }
}
