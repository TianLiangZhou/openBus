<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 18:27
 */

namespace App\Providers;


use App\Subscriber\EventSubscriber;
use App\Subscriber\TextSubscriber;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SubscriberProvider implements ServiceProviderInterface
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
        $pimple['subscriber'] = function() use ($pimple) {
            $config = $pimple->get('config');
            return [
                new EventSubscriber($config),
                new TextSubscriber($config),
            ];
        };
    }
}