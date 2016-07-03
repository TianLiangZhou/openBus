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
}