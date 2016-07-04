<?php

namespace App\Http\Controllers;

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
        if ($bmwxin->)
        if ($request->isPost()) {
            $post = $request->getParsedBody();
        }
        $response->getBody()->write('success');
        return $response;
    }
}