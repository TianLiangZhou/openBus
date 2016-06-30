<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/6/30
 * Time: 16:41
 */

namespace App\Providers;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MonologProvider implements ServiceProviderInterface
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
        $config = $pimple->get('settings');
        if (!isset($config['logger'])) {
            throw new \InvalidArgumentException('Logger configuration not found');
        }
        $pimple['logger'] = new Logger($config['logger']['name']);
        $pimple['logger']->pushHandler(new StreamHandler($config['logger']['path'], $config['logger']['level']));
    }
}