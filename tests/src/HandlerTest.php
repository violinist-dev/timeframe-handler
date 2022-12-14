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
            'timeframe_disallowed' => '00:00-23:59',
        ]);
        self::assertTrue(Handler::isDisallowed($config));
        self::assertFalse(Handler::isAllowed($config));
    }

    public function testOneMinute()
    {
        // I guess this test can fail one minute every day🤷.
        $config = new Config();
        $config->setConfig((object) [
            'timeframe_disallowed' => '00:00-00:01',
        ]);
        self::assertFalse(Handler::isDisallowed($config));
    }

    public function testWholeDayPastMidnight()
    {
        $config = new Config();
        $config->setConfig((object) [
            'timeframe_disallowed' => '00:10-00:09',
        ]);
        self::assertTrue(Handler::isDisallowed($config));
    }

    public function testCrapTimezone()
    {
        $config = new Config();
        $config->setConfig((object) [
            'timezone' => 'derpzone',
        ]);
        self::assertFalse(Handler::isDisallowed($config));
    }

    public function testFromTimeAllowed()
    {
        $config = new Config();
        $timezone = new \DateTimeZone('+0200');
        $date = new \DateTime('now', $timezone);
        $date2 = new \DateTime('now', new \DateTimeZone('+0000'));
        $config->setConfig((object) [
            'timeframe_disallowed' => sprintf('%s-%s', $date2->modify('-1 hour')->format('H:i'), $date2->modify('+2 hour')->format('H:i')),
        ]);
        self::assertTrue(Handler::isAllowedFromTime($date, $config));
    }
}
