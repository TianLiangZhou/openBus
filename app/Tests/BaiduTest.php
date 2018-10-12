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
        $this->assertEmpty($line);
    }

    public function testGetLine()
    {
        $baidu = new Baidu("8377E1ab1af3582362d0b75e99bdea7c");
        $line = $baidu->getBusLine("2", "珠海");
        print_r($line);
        $this->assertEmpty($line);
    }

    public function testMatch()
    {
        $content = '37路';
        preg_match('/[0-9]+[路|线|号线]+/', $content, $match);

        $this->assertTrue($match !== null);
    }
}
