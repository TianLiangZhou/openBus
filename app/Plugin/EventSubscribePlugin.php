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
use Shrimp\Response\ImageResponse;

class EventSubscribePlugin
{
    /**
     * @var array
     */
    private array $config;
    /**
     * @var string
     */
    private string $defaultSubscribeMessage = <<<EOF
感谢你关注城市公交通公众号\n
公交线路如: 37, 37路, 1号线, 37_杭州, 37_广州, 地铁(如: 1号线，4号线，6号线_广州)\n
换乘查询如: 起点_终点, 起点_终点_城市, 如(文一路_西湖文化广场)\n
分隔符可以为: -, _, ?, |, $, #, @, &, %, ~。如(37|广州)
EOF;
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
        $response->setResponse(
            new ImageResponse($response->getMessageSource(), "sU4rBCc11U1kwOiaC0YuYLG9DpnmQTUVAcCmeJdv_no")
        );
    }
}
