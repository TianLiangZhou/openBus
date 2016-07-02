<?php

namespace App\Http\Controllers;

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
        $sign = $this->verifyWeixin($query);
        $response->getBody()->write('success');
        $post = $request->getParsedBody();
        $this->container->get('logger')->info(var_export($post, true));
        return $response;
    }
}