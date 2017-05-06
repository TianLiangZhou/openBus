<?php

namespace App\Http\Controllers;

use App\Lib\Aibang;
use App\Lib\Baidu;
use App\Providers\EventSubscriber;
use App\Providers\TextSubscriber;
use Bmwxin\MessageDispatcher;
use Slim\Http\Request;
use Slim\Http\Response;
use Bmwxin\MpSDK;


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
        $verify = MpSDK::verifyRequest($this->config['weixin']['token'], $query);
        if ($verify == false && $this->config['debug'] == false) {
            return $response->write('error');
        }
        $response = $response->withHeader('Content-Type', 'text/xml; charset=utf-8');
        $messageResponse = 'success';
        if ($request->isPost()) {
            $package = $request->getParsedBody();
            if ($package instanceof \SimpleXMLElement) {
                $messageResponse = $this->container['dispatcher']($package);
            }
        }
        $response->write($messageResponse);
        return $response;
    }
}