<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 10:51
 */

namespace App\Subscriber;


use App\Lib\Baidu;
use Bmwxin\Message\MessageSubscriberInterface;
use Bmwxin\Message\MessageType;
use Bmwxin\Response;

class TextSubscriber implements MessageSubscriberInterface
{
    private $defaultSplit = [
        '-', '_', '?', '|',
        '$', '#', '@', '&',
        '%', '~'
    ];

    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function onMessageType(Response $response, $package)
    {
        $content = $this->splitContent(trim((string) $package->Content));
        $line = false;
        if (is_numeric($content[0])) {
            $line = true;
        }
        if (preg_match('/[0-9]+[路|线|号线]+/is', $content[0])) {
            $line = true;
        }
        $baiDu = new Baidu($this->config['baidu']['secret']);
        $result = null;
        if ($line) {
            $result = $baiDu->getBusLine($content[0], isset($content[1]) ? $content[1] : '杭州');
        } else {
            $result = $baiDu->getLineInfo($content[0], isset($content[1]) ? $content[1] : $content[0], isset($content[2]) ? $content['2'] : '杭州');
        }
        $responseMessage = '系统无法识别此线路';
        $message = null;
        if (!empty($result)) {
            if ($line) {
                foreach ($result as $key => $value) {
                    if (is_string($value)) {
                        $message .= '线路: ' . $value . "\n";
                    }
                    if (is_array($value)) {
                        $message .= '站点: ' . implode(' -> ', $value) . "\n\n";
                    }
                }
            } else {
                foreach ($result as $value) {
                    if (is_string($value)) {
                        $message .= ',' . $value . ',';
                    }
                    if (is_array($value)) {
                        $message .= '[' . $value['start_name'] . '] -> ' .
                            '乘坐' . $value['name'] .
                            ' -> [' . $value['end_name'] . ']';
                    }
                }
            }
            $responseMessage = trim($message, ',');
        }
        $textResponse = new Response\TextResponse($package);
        $textResponse->setContent($responseMessage);
        $response->setContent($textResponse);
    }

    /**
     * @param $message
     * @return array
     */
    private function splitContent($message)
    {
        foreach ($this->defaultSplit as $value) {
            if (strpos($message, $value) !== false) {
                return explode($value, $message);
            }
        }
        return [$message];
    }

    public function getSubscriberType()
    {
        // TODO: Implement getSubscriberType() method.

        return [
            MessageType::TEXT => ['onMessageType', 1]
        ];
    }
}