<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 14:23
 */

namespace App\Plugin;


use Psr\Container\ContainerInterface;
use Shrimp\Event\ResponseEvent;
use Shrimp\Response\NewsResponse;

class EventSubscribePlugin
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * EventSubscribePlugin constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get('config');
    }

    public function __invoke(ResponseEvent $response)
    {
        // TODO: Implement __invoke() method.
        // $response->setResponse($this->defaultSubscribeMessage);
        $articles = [
            "title" => "公众号使用方法",
            "description" => "【回复】2路_上海，获取上海2路公交信息。【回复】八达岭长城_天安门东门_北京，获取长城到北京天安门的线路规化...",
            "pic_url" => "https://mmbiz.qpic.cn/mmbiz_jpg/c55CzXLykEojvohia8TTyicKkjPKxicWaOdqBUODRnODhpHwEFMSicN3ic3icoTl6ouagFMJJEBPqGjMib0echicgiaiaWVQ/0?wx_fmt=jpeg",
            "url" => "https://mp.weixin.qq.com/s?__biz=MjM5OTgzNjg2Mg==&mid=100000027&idx=1&sn=18821082d86e6714bd335fa0f491bd48&chksm=27342cca1043a5dc224224468258dc2f0d64a4de4725f9003c775ffdae80a645c5c66d80c36c#rd"
        ];
        $response->setResponse(
            new NewsResponse($response->getMessageSource(), $articles)
        );
    }
}
