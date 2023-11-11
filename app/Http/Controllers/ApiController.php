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
                'diagram'   => 'https://t.cncnimg.cn/img/ditie/map/changsha.jpg',
            ],
            "changzhou" => [
                'timetable' => '',
                'diagram'   => 'http://www.czmetro.net.cn/Html/images/road/roadImg2.jpg',
            ],
            "chengdu" => [
                'timetable' => 'https://www.chengdurail.com/ckfw/smbcskb.htm',
                'diagram'   => 'https://www.chengdurail.com/images/guanwangxianwangtu_00.png',
            ],
            "chongqing" => [
                'timetable' => 'https://www.cqmetro.cn/app-smbsj.shtml',
                'diagram'   => 'https://www.cqmetro.cn/imgs/%E7%BA%BF%E8%B7%AF%E5%85%A8.png',
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
                'diagram'   => 'https://www.gyurt.com/materialpub/gdyy/images/metroLineImg.jpg',
            ],
            "haerbin" => [
                'timetable' => 'https://mp.weixin.qq.com/s/5s8Z4eqmC3EF1zjjozMT9g',
                'diagram'   => 'https://mmbiz.qpic.cn/mmbiz_jpg/t56pBf0lCibZ6Mwwtm2FUnOh3ju9c2MibptGibiavPz8HLmp6oj4kQsKAlWhKqpUiaVWqVxDwia8licPPvTNiczWk2yFVg/640?wx_fmt=jpeg&wxfrom=5&wx_lazy=1&wx_co=1',
            ],
            "hangzhou" => [
                'timetable' => 'https://wx.hzmetro.com/',
                'diagram'   => 'https://cdnjd.zhangzhengyun.com/hzdt/big.png?v=1',
            ],
            "hefei" => [
                //https://www.hfgdjt.com/yHNGS6rOOFgAIkO1NiScWlFOJj5i6IduxS%2BScMu6afgc1gXNgWl9n9hYJ_aQrCvk?encrypt=1
                'timetable' => '',
                'diagram'   => 'https://www.hfgdjt.com/1ywuKELSO2ahQuWZ/pr/0/r/e00a0e273cb8/YagM72orAMwIpLDWIOTB4jBGDK2z4ophe9Wx8RaBwYpCGDkU3knr4UdAk_nH0VPtuE7UeY75Lw7rYCxe1s01KKNac2XsIcI0ps8KYDL2Hvk%3D/020230407152933_712480.jpg',
            ],
            "huhehaote" => [
                // https://mp.weixin.qq.com/s/ZW8DBvWtd0S9stX8r0NRxA
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
                'timetable' => 'https://zhcx.km-metro.com/metro-schedule',
                'diagram'   => '',
            ],
            "lanzhou" => [
                // https://www.lzgdjt.com/lzgd/mobile/mobile-serve.jsp
                'timetable' => '',
                'diagram'   => '',
            ],
            "luoyang" => [
                'timetable' => 'https://www.lysubway.com.cn/service.html#home3',
                'diagram'   => 'https://www.lysubway.com.cn/res/image/202109/13/13102016_8366.png',
            ],
            "nanchang" => [
                'timetable' => 'https://guidaometro.banlvit.com/line/metrodate',
                'diagram'   => '',
            ],
            "nanjing" => [
                // https://www.njmetro.com.cn/njdtweb/home/go-operate-center.do?tag=yxskb
                'timetable' => '',
                'diagram'   => 'https://www.njmetro.com.cn/njdtweb/dtweb/images/map_new.jpg',
            ],
            "nanning" => [
                'timetable' => 'http://www.nngdjt.com/html/service1b/indexwx.html',
                'diagram'   => 'http://www.nngdjt.com/images/yyfw/line5_lar.png',
            ],
            "nantong" => [
                // https://service.ntrailway.com/#/OperatingTime
                'timetable' => '',
                //
                'diagram'   => 'https://service.ntrailway.com/api/ntopen/ignoreGateway/fastDfs/browse?fileName=group1%2FM00%2F00%2F00%2FrBVxA2MruW2ANDSWAA4xHgiuHtc855%5E%5E%5E%5E%E7%BA%BF%E8%B7%AFi%E5%9B%BE.png',
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
