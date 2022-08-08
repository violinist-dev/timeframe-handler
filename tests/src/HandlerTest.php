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
}
