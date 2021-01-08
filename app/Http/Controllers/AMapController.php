<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use App\Services\AMapService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AMapController
 * @package App\Http\Controllers
 */
class AMapController extends BaseController
{

    private AMapService $amap;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->amap = new AMapService($container);
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function poi(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->poi($request->getParsedBody());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function stationLine(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->stationLine($request->getParsedBody());
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function lineStation(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->lineStation($request->getParsedBody());
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function line(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->line($request->getParsedBody());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function lineEx(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->lineEx($request->getParsedBody());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function nearLine(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->amap->nearLine($request->getParsedBody());
    }

    /**
     *
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function poiLite(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->poiLite($request->getQueryParams());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function locationByIp(ServerRequestInterface $request, ResponseInterface $response)
    {
        $servers = $request->getServerParams();
        return $this->amap->locationByIp($servers['REMOTE_ADDR']);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function locationByAddress(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->locationByAddress($request->getQueryParams()['address'] ?? '');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function city(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->amap->city();
    }
}
