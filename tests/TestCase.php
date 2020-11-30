<?php
declare(strict_types=1);

namespace App\Tests;

use App\Lib\Baidu;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected ?Baidu $bd = null;

    protected function setUp(): void
    {
        $this->bd = new Baidu("8377E1ab1af3582362d0b75e99bdea7c");
    }
}
