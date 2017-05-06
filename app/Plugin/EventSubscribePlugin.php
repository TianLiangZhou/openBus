<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 14:23
 */

namespace App\Plugin;


use Bmwxin\Message\MessageType;
use Bmwxin\Response\ResponsePluginInterface;
use Bmwxin\Response\ResponseInterface;
use Bmwxin\Response\TextResponse;

class EventSubscribePlugin implements ResponsePluginInterface
{
    private $config = [];
    private $defaultSubscribeMessage = <<<EOF
感谢你关注城市公交通公众号\n
公交站点如: 37, 37路, 1号线, 37_杭州, 37_广州\n
公交线路如: 起点_终点, 起点_终点_城市, 如(文一路_西湖文化广场)\n
分隔符可以为: -, _, ?, |, $, #, @, &, %, ~。如(37|广州)
EOF;
    public function __construct($config)
    {
        $this->config = $config;
    }
    /**
     * @param $package
     * @return ResponseInterface
     */
    public function getResponse($package): ResponseInterface
    {
        // TODO: Implement getResponse() method.
        return (new TextResponse($package))->setContent($this->defaultSubscribeMessage);
    }

    public function type(): string
    {
        // TODO: Implement type() method.
        return MessageType::EVENT;
    }

    public function name(): string
    {
        // TODO: Implement name() method.
        return MessageType::SUBSCRIBE;
    }
}