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
                'apps' => $this->getApps(),
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
                'timetable' => 'https://www.bjsubway.com/mobile/station/xltcx/line1/2013-08-19/4.html',
                'diagram'   => 'https://www.bjsubway.com/subway/images/lwt.png?1',
            ],
            "changchun" => [
                'timetable' => 'https://mp.weixin.qq.com/s/M5tTOSO0HrYUGVDGGqRTUQ',
                'diagram'   => '',
            ],
            "changsha" => [
                'timetable' => 'https://mp.weixin.qq.com/s/aosPVB4Mfae_xELqb7BVMw',
                'diagram'   => '',
            ],
            "changzhou" => [
                'timetable' => '',
                'diagram'   => '',
            ],
            "chengdu" => [
                'timetable' => 'https://www.chengdurail.com/ckfw/smbcskb.htm',
                'diagram'   => 'https://www.chengdurail.com/images/guanwangxianwangtu_00.png',
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
                'diagram'   => 'https://www.dlmetro.com/hb-air-web/html/aboutus/img/linenet.jpg',
            ],
            "dongguan" => [
                'timetable' => 'https://h5.dongguantong.com.cn/subwayService/index.html#/subway/timeline',
                'diagram'   => '',
            ],
            "foshan" => [
                'timetable' => 'https://www.fmetro.net/xlyy/lcskb',
                'diagram'   => 'https://www.fmetro.net/upload/main/contentmanage/node/image/e3ff611db9574c24a974430f1076ad6c.png',
            ],
            "fuzhou" => [
                'timetable' => 'http://www.fzmtr.com/cms/sitemanage/applicationIndex.shtml?applicationName=fzdt/metroSite&pageName=pageMetroSiteTimes&lineName=1%E5%8F%B7%E7%BA%BF&id=150418954957920000&siteId=110421022978370000',
                'diagram'   => '',
            ],
            "guangzhou" => [
                'timetable' => '',
                'diagram'   => 'https://proxy.alenable.com/subwayMap/static/map_gz_new.jpg',
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
