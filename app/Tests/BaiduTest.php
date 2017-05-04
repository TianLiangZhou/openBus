<?php
namespace App\Tests;
use App\Lib\Aibang;
use App\Lib\Baidu;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 13:19
 */
class BaiduTest extends PHPUnit_Framework_TestCase
{
    public function testGetLineInfo()
    {
        $baidu = new Baidu("8377E1ab1af3582362d0b75e99bdea7c");
        $line = $baidu->getLineInfo("三墩镇", "天堂软件园");
        print_r($line);
        //echo $baidu->getBusLine('37');
        $content = '37路';
        if (preg_match('/[0-9]+[路|线|号线]+/', $content)) {
            echo 'aaa';
        }
        $this->assertEmpty($line);
    }
}
