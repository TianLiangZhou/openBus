<?php
declare(strict_types=1);
namespace App\Tests;

use App\Lib\Baidu;

/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2017/5/4
 * Time: 13:19
 */
class BaiduTest extends TestCase
{
    public function testGetLineInfo()
    {
        $line = $this->bd->getLineInfo("三墩镇", "天堂软件园");
        $this->assertEmpty($line);
    }

    public function testGetLine()
    {
        $line = $this->bd->getBusLine("2", "珠海");
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
