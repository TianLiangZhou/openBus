<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 10:51
 */

namespace App\Plugin;


use App\Exception\LineException;
use App\Lib\Baidu;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerInterface;
use Shrimp\Event\ResponseEvent;
use Shrimp\Response\NewsResponse;

class TextPlugin
{
    private array $defaultSplit = [
        '-', '_', '?', '|',
        '$', '#', '@', '&',
        '%', '~', '/', '%',
        '^', '*', '=', '+',
        '.', ' ', ','
    ];

    private array $config;

    private string $defaultCity = '杭州市';

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;


    /**
     * TextPlugin constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get('config');
    }
    /**
     * @param $message
     * @return array
     */
    private function splitContent($message): array
    {
        foreach ($this->defaultSplit as $value) {
            if (strpos($message, $value) !== false) {
                return explode($value, $message);
            }
        }
        return [$message];
    }

    /**
     * @param ResponseEvent $response
     */
    public function __invoke(ResponseEvent $response)
    {
        // TODO: Implement __invoke() method.
        $receiveData = trim((string) $response->getAttribute("Content"));
        if ($receiveData == "小程序") {
            $miniapp = sprintf(
                '<a data-miniprogram-appid="%s" data-miniprogram-path="pages/index/index" href="http://www.qq.com">%s小程序</a>',
                $this->config['miniapp']['appid'],
                $this->config['name'],
            );
            $response->setResponse($miniapp);
            return ;
        }
        $content = $this->splitContent($receiveData);
        $line = false;
        if (is_numeric($content[0])) {
            $line = true;
        }
        if (preg_match('/[a-z]?[0-9]+([路|线|号线]+)?/is', $content[0])) {
            $line = true;
        }
        $baiDu = new Baidu($this->config['baidu']['secret']);
        $result = null;
        // $responseMessage = $this->defaultMessage;
        $responseMessage = null;
        try {
            if ($line) {
                $result = $baiDu->getBusLine($content[0], isset($content[1]) ? $content[1] : $this->defaultCity);
            } else {
                $result = $baiDu->getLineInfo($content[0], isset($content[1]) ? $content[1] : $content[0], isset($content[2]) ? $content[2] : $this->defaultCity);
            }
        } catch (LineException $e) {
            $responseMessage = $e->getMessage();
        } catch (GuzzleException $e) {
            $responseMessage = "查询超时，请稍后再试";
        }
        if ($responseMessage) {
            $response->setResponse($responseMessage);
        }
        if (empty($result)) {
            $articles = [
                "title" => "公众号使用方法",
                "description" => "【回复】2路_上海，获取上海2路公交信息。【回复】八达岭长城_天安门东门_北京，获取长城到北京天安门的线路规化...",
                "pic_url" => "https://mmbiz.qpic.cn/mmbiz_jpg/c55CzXLykEojvohia8TTyicKkjPKxicWaOdqBUODRnODhpHwEFMSicN3ic3icoTl6ouagFMJJEBPqGjMib0echicgiaiaWVQ/0?wx_fmt=jpeg",
                "url" => "https://mp.weixin.qq.com/s?__biz=MjM5OTgzNjg2Mg==&mid=100000027&idx=1&sn=18821082d86e6714bd335fa0f491bd48&chksm=27342cca1043a5dc224224468258dc2f0d64a4de4725f9003c775ffdae80a645c5c66d80c36c#rd"
            ];
            $response->setResponse(
                new NewsResponse($response->getMessageSource(), $articles)
            );
            return ;
        }
        $message = null;
        if ($line) {
            foreach ($result as $key => $value) {
                if ($key > 1) break;
                $message .= '线路: ' . $value['name'] . "\n";
                $message .= '时间: ' . $value['time'] . "\n";
                $message .= '票价: ' . ($value['price'] / 100) . "元\n";
                $message .= '站点: ' . implode(' -> ', $value['station']) . "\n\n";
            }
        } else {
            foreach ($result as $value) {
                if (is_string($value)) {
                    $message .= ',' . $value . ',';
                }
                if (is_array($value)) {
                    $message .= '[' . $value['start_name'] . '] -> ' .
                        '乘坐' . $value['name'] .
                        ' -> [' . $value['end_name'] . ']';
                }
            }
        }
        $responseMessage = trim($message, ',');
        $response->setResponse($responseMessage);
    }
}
