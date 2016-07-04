<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/4
 * Time: 20:31
 */

namespace App\Lib;


use Bmwxin\AbstractReceive;

class Reply extends AbstractReceive
{

    /**
     * @param $xml
     * @return mixed
     */
    public function text($xml)
    {
        // TODO: Implement text() method.
        $reply = [
            'toUser' => $xml->ToUserName,
            'fromUser' => $xml->FromUserName,
            'content' => 'aaaa'
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
}