<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 14:23
 */

namespace App\Subscriber;


use Bmwxin\Message\MessageSubscriberInterface;
use Bmwxin\Message\MessageType;
use Bmwxin\Response;

class EventSubscriber implements MessageSubscriberInterface
{
    private $config = [];
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function onMessageEvent(Response $response, $package)
    {

    }


    public function getSubscriberType()
    {
        // TODO: Implement getSubscriberType() method.

        return [
            MessageType::EVENT => ['onMessageEvent', 1]
        ];
    }
}