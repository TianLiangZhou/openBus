<?php

namespace App\Http\Controllers;

use App\Lib\Reply;
use Bmwxin\Bmwxin;
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
        $bmwxin = new Bmwxin(
            $this->config['weixin']['appid'], 
            $this->config['weixin']['secret']
        );
        $query = $request->getQueryParams();
        $message = 'success';
        if ($bmwxin->verifyWeixinRequest($this->config['weixin']['token'], $query)) {
            if ($request->isPost()) {
                $post = $request->getParsedBody();
                $message = $bmwxin->registerReceiveMessage($post, new Reply());
                if ($message) {
                    $response = $response->withHeader('Content-Type', 'text/xml; charset=utf-8');
                }
            }
        }
        $response->write($message);
        return $response;
    }
}