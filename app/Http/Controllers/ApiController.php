<?php


namespace App\Http\Controllers;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends BaseController
{

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return JsonResponse
     */
    public function apps(RequestInterface $request, ResponseInterface $response): JsonResponse
    {
        $staticDomain = $this->config['static_domain'];
        $apps = [
            [
                "id" => 1,
                "appid" => "wxbb58374cdce267a6",
                "name" => "乘车码",
                "path" => "",
                "icon" => $staticDomain . "/bus/ccm.png",
            ],
            [
                "id" => 2,
                "appid" => "wxaf35009675aa0b2a",
                "name" => "滴滴",
                "path" => "",
                "icon" => $staticDomain . "/bus/dd.png",
            ],
            [
                "id" => 3,
                "appid" => "wxbc0cf9b963bd3550",
                "name" => "高德",
                "path" => "",
                "icon" => $staticDomain . "/bus/gd.png",
            ],
            [
                "id" => 4,
                "appid" => "",
                "name" => "站点地图",
                "path" => "../map/map?type=near",
                "icon" => $staticDomain . "/bus/line.png",
            ]
        ];
        $body = [
            'code' => 0,
            'data' => $apps,
        ];
        return new JsonResponse($body);
    }
}
