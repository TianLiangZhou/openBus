<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 18:27
 */

namespace App\Providers;


use App\Subscriber\EventSubscribePlugin;
use App\Subscriber\TextPlugin;
use Bmwxin\MessageDispatcher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

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
        $plugins = [];
        if (isset($config['plugins'])) {
            foreach ($config['plugins'] as $plugin) $plugins[] = new $plugin($config);
        }
        $pimple['dispatcher'] = $pimple->protect(function($package) use ($plugins) {
            $dispatcher =  new MessageDispatcher($package);
            if ($plugins) $dispatcher->addPlugins($plugins);
            return $dispatcher->dispatch();
        });
    }
}