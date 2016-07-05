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
        $splitMessage = $this->splitMessage($xml->Content);
        $baidu = new Baidu($this->c->get('config')['baidu']['ak']);
        $line = [];
        switch (count($splitMessage)) {
            case 1:
                
                break;
            case 2:
                $line = $baidu->getLineInfo($splitMessage[0], $splitMessage[1]);
                break;
            case 3:
                $line = $baidu->getLineInfo($splitMessage[0], $splitMessage[1], $splitMessage[2]);
                break;
            case 4:
                break;
        }
        $message = $this->defaultMessage();
        if (!empty($line)) {
            $message = $line[count($line) - 1];
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