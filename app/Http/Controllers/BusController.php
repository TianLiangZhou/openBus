<?php

namespace App\Http\Controllers;

use Shrimp\ShrimpWechat;
use Slim\Http\Request;
use Slim\Http\Response;


/**
 * Class BusController
 * @author zhoutianliang <mfkgdyve@gmail.com>
 * @package App\Http\Controllers
 */
class BusController extends BaseController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function receive(Request $request, Response $response)
    {
        $query = $request->getQueryParams();
        $verify = ShrimpWechat::verifyRequest($this->config['weixin']['token'], $query);
        if ($verify == false && $this->config['debug'] == false) {
            return $response->write('error');
        }
        if ($this->config['debug'] && $verify) {
            return $response->write($query['echostr']);
        }
        $response = $response->withHeader('Content-Type', 'text/xml; charset=utf-8');
        $messageResponse = 'success';
        if ($request->isPost()) {
            $messageResponse = $this->container['dispatcher']();
        }
        $response->write($messageResponse);
        return $response;
    }
}