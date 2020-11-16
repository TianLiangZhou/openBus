<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 10:51
 */

namespace App\Plugin;


use App\Exception\LineException;
use App\Lib\Baidu;
use GuzzleHttp\Exception\GuzzleException;
use Shrimp\Event\ResponseEvent;

class TextPlugin
{
    private array $defaultSplit = [
        '-', '_', '?', '|',
        '$', '#', '@', '&',
        '%', '~', '/', '%',
        '^', '*', '=', '+',
        '.', ' ', ','
    ];

    private array $config;

    private string $defaultMessage = <<<EOF
无法识别此消息(查询须知)\n
公交线路如: 37, 37路, 1号线, 37_杭州, 37_广州, 地铁(如: 1号线，4号线，6号线_广州)\n
换乘查询如: 起点_终点, 起点_终点_城市, 如(文一路_西湖文化广场)\n
分隔符可以为: -, _, ?, |, $, #, @, &, %, ~。如(37|广州)
EOF;

    private string $defaultCity = '杭州';


    public function __construct($config)
    {
        $this->config = $config;
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

    /**
     * @param ResponseEvent $response
     */
    public function __invoke(ResponseEvent $response)
    {
        // TODO: Implement __invoke() method.
        $content = $this->splitContent(trim((string) $response->getAttribute("Content")));
        $line = false;
        if (is_numeric($content[0])) {
            $line = true;
        }
        if (preg_match('/[a-z]?[0-9]+([路|线|号线]+)?/is', $content[0])) {
            $line = true;
        }
        $baiDu = new Baidu($this->config['baidu']['secret']);
        $result = null;
        $responseMessage = $this->defaultMessage;
        try {
            if ($line) {
                $result = $baiDu->getBusLine($content[0], isset($content[1]) ? $content[1] : $this->defaultCity);
            } else {
                $result = $baiDu->getLineInfo($content[0], isset($content[1]) ? $content[1] : $content[0], isset($content[2]) ? $content[2] : $this->defaultCity);
            }
        } catch (LineException $e) {
            $responseMessage = $e->getMessage();
        } catch (GuzzleException $e) {
            $responseMessage = "查询超时，请稍后再试";
        }
        $message = null;
        if (!empty($result)) {
            if ($line) {
                foreach ($result as $key => $value) {
                    if ($key > 1) break;
                    $message .= '线路: ' . $value['name'] . "\n";
                    $message .= '时间: ' . $value['time'] . "\n";
                    $message .= '票价: ' . ($value['price'] / 100) . "元\n";
                    $message .= '站点: ' . implode(' -> ', $value['station']) . "\n\n";
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
        $response->setResponse($responseMessage);
    }
}
