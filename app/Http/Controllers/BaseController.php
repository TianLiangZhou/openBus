<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/1
 * Time: 17:45
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use Psr\Container\ContainerInterface;

abstract class BaseController
{
    protected ContainerInterface $container;
    
    protected $config = [];

    /**
     * BaseController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        
        $this->config = $this->container->get('config');
    }
}
