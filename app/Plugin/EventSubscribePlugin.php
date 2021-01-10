<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 14:23
 */

namespace App\Plugin;


use Shrimp\Event\ResponseEvent;
use Shrimp\Response\NewsResponse;

/**
 * Class EventSubscribePlugin
 * @package App\Plugin
 */
class EventSubscribePlugin extends Plugin
{
    public function __invoke(ResponseEvent $response)
    {
        // TODO: Implement __invoke() method.
        $response->setResponse(
            new NewsResponse($response->getMessageSource(), $this->articles)
        );
    }
}
