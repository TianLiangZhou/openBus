<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/4
 * Time: 20:31
 */

namespace App\Lib;


use Bmwxin\AbstractReceive;
use Slim\Container;

class Reply extends AbstractReceive
{
    /**
     * @var null|Container
     */
    protected $c = null;
    /**
     * @var array
     */
    protected $defaultSplit = [
        '-', '_', '?', '|',
        '$', '#', '@', '&',
        '%', '~'
    ];
    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function text($xml)
    {
        // TODO: Implement text() method.
        $splitMessage = trim($this->splitMessage($xml->Content));
        $baidu = new Baidu($this->c->get('config')['baidu']['secret']);
        $aibang = new Aibang($this->c->get('config')['aibang']['secret']);
        $line = [];
        $lineDetailMatch = [];
        preg_match('/\d{1,}路|\d{1,}/is', $splitMessage[0], $lineDetailMatch);
        
        switch (count($splitMessage)) {
            case 1:
                if (!empty($lineDetailMatch)) {
                    $line = $aibang->getBusLineStatsDetail($lineDetailMatch[0]);
                }
                break;
            case 2:
                if (!empty($lineDetailMatch)) {
                    $line = $aibang->getBusLineStatsDetail($lineDetailMatch[0], $splitMessage[1]);
                } else {
                    $line = $baidu->getLineInfo($splitMessage[0], $splitMessage[1]);
                }
                break;
            case 3:
                $line = $baidu->getLineInfo($splitMessage[0], $splitMessage[1], $splitMessage[2]);
                break;
            case 4:
                break;
        }
        $message = $this->defaultMessage();
        if (!empty($line)) {
            $tmp = '';
            if (!empty($lineDetailMatch)) {
                foreach ($line as $value) {
                    $tmp = '线路:' . $value['name'] . "\n" .
                         '时间:' . $value['info'] . "\n" . 
                         "站点:\n" . 
                         implode(' -> ', $value['stats']) . "\n\n";
                }
            } else {
                foreach ($line as $value) {
                    if (is_string($value)) {
                        $tmp .= ',' . $value . ',';
                    }
                    if (is_array($value)) {
                        $tmp .= '[' . $value['start_name'] . '] -> ' .
                            '乘坐' . $value['name'] .
                            ' -> [' . $value['end_name'] . ']';
                    }
                }
            }
            $message = trim($tmp, ',');
        }
        $reply = [
            'toUser' => $xml->FromUserName,
            'fromUser' => $xml->ToUserName,
            'content' => $message
        ];
        return $this->formatMessage('text', $reply);
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function image($xml)
    {
        // TODO: Implement image() method.
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function voice($xml)
    {
        // TODO: Implement voice() method.
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function shortVideo($xml)
    {
        // TODO: Implement shortVideo() method.
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function location($xml)
    {
        // TODO: Implement location() method.
    }
    
    protected function defaultMessage()
    {
        return '系统无法识别此线路';
    }

    /**
     * @param $message
     * @return array
     */
    protected function splitMessage($message)
    {
        foreach ($this->defaultSplit as $value) {
            if (strpos($message, $value) !== false) {
                return explode($value, $message);
            }
        }
        return [];
    }
}