<?php


namespace App\Http\Controllers;


use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends BaseController
{

    /**
     * @return JsonResponse
     */
    public function apps(): JsonResponse
    {
        $body = [
            'code' => 0,
            'data' => $this->getApps(),
        ];
        return new JsonResponse($body);
    }

    /**
     * @return JsonResponse
     */
    public function configuration(): JsonResponse
    {
        $body = [
            'code' => 0,
            'data' => [
                'apps' => $this->apps(),
                'subway' => $this->getSubway(),
            ],
        ];
        return new JsonResponse($body);
    }

    /**
     * @return array[]
     */
    private function getSubway(): array
    {
        return [
            "aomen" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "beijing" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "changchun" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "changsha" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "changzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "chengdu" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "chongqing" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "chuzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "dalian" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "dongguan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "foshan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "fuzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "guangzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "guiyang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "haerbin" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "hangzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "hefei" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "huhehaote" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "jinan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "jinhua" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "kunming" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "lanzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "luoyang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "nanchang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "nanjing" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "nanning" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "nantong" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "ningbo" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "qingdao" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "shanghai" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "shaoxing" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "shenyang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "shenzhen" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "shijiazhuang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "suzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "taiyuan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "taizhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "tianjin" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "wenzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "wuhan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "wuhu" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "wulumuqi" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "wuxi" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xiamen" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xian" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xianggang" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xiangtan" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xiangxi" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "xuzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "zhengzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
        ];
    }

    /**
     * @return array[]
     */
    private function getApps(): array
    {
        $staticDomain = $this->config['static_domain'];
        return [
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
    }
}
