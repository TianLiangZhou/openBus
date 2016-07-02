<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/1
 * Time: 17:45
 */

namespace App\Http\Controllers;


use Slim\Container;

class BaseController
{
    protected $container = null;
    
    protected $config = [];

    /**
     * BaseController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        
        $this->config = $this->container->get('config');
    }

    /**
     * @param array $query
     * @return string
     */
    protected function verifyWeixin(array $query)
    {
        $weixin = [$this->config['weixin']['token'], $query['timestamp'], $query['nonce']];
        sort($weixin, SORT_STRING);
        $sign = sha1(implode($weixin, ''));
        if ($sign == $query['signature']) {
            return true;
        }
        return false;
    }
}