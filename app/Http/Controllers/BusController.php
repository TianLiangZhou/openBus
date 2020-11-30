<?php

namespace App\Http\Controllers;

use App\Plugin\EventSubscribePlugin;
use App\Plugin\TextPlugin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shrimp\Message\Event;
use Shrimp\ShrimpWechat;


/**
 * Class BusController
 * @author zhoutianliang <mfkgdyve@gmail.com>
 * @package App\Http\Controllers
 */
class BusController extends BaseController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function receive(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $query = $request->getQueryParams();
        $verify = ShrimpWechat::verifyRequest($this->config['weixin']['token'], $query);
        if ($verify == false && $this->config['debug'] == false) {
            $response->getBody()->write('error');
            return $response;
        }
        if ($this->container->has('logger')) {
            $this->container->get('logger')->info(json_encode(['verify' => $verify, 'debug' => $this->config['debug']]));
            $this->container->get('logger')->info(json_encode($query));
        }
        if ($this->config['verify_mode'] && $verify) {
            $response->getBody()->write($query['echostr'] ?? '');
            return $response;
        }
        $response = $response->withHeader('Content-Type', 'text/xml; charset=utf-8');
        $messageResponse = 'success';
        if ($request->getMethod() === 'POST') {
            $dispatcher = new ShrimpWechat($this->config['weixin']['appid'], $this->config['weixin']['secret']);
            $dispatcher->bind(new TextPlugin($this->config));
            $dispatcher->bind(new EventSubscribePlugin($this->config), Event::EVENT_SUBSCRIBE);
            $messageResponse = $dispatcher->send();
        }
        $response->getBody()->write($messageResponse);
        return $response;
    }
}
