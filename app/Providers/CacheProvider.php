<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/1
 * Time: 21:58
 */

namespace App\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CacheProvider implements ServiceProviderInterface
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
        $pimple['cache'] = function (Container $container) {
            $config = $container->get('config');
            if (empty($config['driver'])) {
                throw new \RuntimeException('cache driver configuration not found');
            }

        };
    }
}