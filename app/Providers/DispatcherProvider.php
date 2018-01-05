<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 18:27
 */

namespace App\Providers;


use App\Plugin\EventSubscribePlugin;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Shrimp\Message\Type;
use Shrimp\ShrimpWechat;

class DispatcherProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        // TODO: Implement register() method.
        $config = $pimple->get('config');
        $pimple['dispatcher'] = $pimple->protect(function() use ($config) {
            $dispatcher =  new ShrimpWechat($config['weixin']['appid'], $config['weixin']['secret']);
            $dispatcher->bind(new \App\Plugin\TextPlugin($config));
            $dispatcher->bind(new EventSubscribePlugin([]), Type::SUBSCRIBE);
            return $dispatcher->send();
        });
    }
}