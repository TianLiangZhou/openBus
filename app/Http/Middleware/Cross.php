<?php


namespace App\Http\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Cross
 * @package App\Http\Middleware
 */
class Cross implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.

        $response = $handler->handle($request);

        if (!$response->hasHeader('Access-Control-Allow-Origin')) {
            $response = $response->withHeader("Access-Control-Allow-Origin", "*")
                ->withHeader("Access-Control-Allow-Methods", "*")
                ->withHeader("Access-Control-Allow-Headers", "DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,key,x-biz,x-info,platinfo,encr,enginever,gzipped,poiid");
        }
        return $response;
    }
}
