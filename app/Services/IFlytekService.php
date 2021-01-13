<?php


namespace App\Services;


use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

/**
 * Class IFlytekService
 * @package App\Services
 */
class IFlytekService
{
    /**
     * @var string
     */
    private string $gateway = 'https://ltpapi.xfyun.cn';

    /**
     * @var string
     */
    private string $secret = '909a778f0f5ad182772e48dc5b6bcdc1';

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @var Client
     */private $client;

    /**
     * IFlytekService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->client = $container->has('client')
            ? $container->get('client')
            : new Client();
    }

    /**
     * @param string $words
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function participle(string $words)
    {
        $body = ['text' => $words];
        $time = time();
        $headers = [
            'X-Appid' => '5f5b2fac',
            'X-CurTime' => $time,
            'X-Param' => base64_encode(json_encode(["type"=>"dependent"])),
        ];
        $headers['X-CheckSum'] = md5($this->secret. $time . $headers['X-Param']);

        $uri = $this->gateway . '/v1/cws';
        $response = $this->client->post($uri, [
            'headers' => $headers,
            'form_params' => $body,
        ]);
        if ($response->getStatusCode() !== 200) {
            return [];
        }
        $result = json_decode($response->getBody()->getContents(), true);
        if (!isset($result['data']['word'])) {
            return [];
        }
        return array_filter($result['data']['word'], function($item) {
            return mb_strlen($item) > 1;
        });
    }

}
