<?php

namespace Violinist\TimeFrameHandler\Tests;

use PHPUnit\Framework\TestCase;
use Violinist\TimeFrameHandler\Handler;
use Violinist\Config\Config;

class HandlerTest extends TestCase
{
    public function testNoConfig()
    {
        self::assertFalse(Handler::isDisallowed(new Config()));
    }

    public function testWholeDay()
    {
        $config = new Config();
        $config->setConfig((object) [
            'timeframe_disallowed' => '00:00-2359',
        ]);
        self::assertTrue(Handler::isDisallowed($config));
    }
}
