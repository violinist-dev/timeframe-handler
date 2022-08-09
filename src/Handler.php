<?php

namespace Violinist\TimeFrameHandler;

use Violinist\Config\Config;

class Handler
{

    public static function isDisallowed(Config $config)
    {
        // Default timezone is UTC.
        $timezone = new \DateTimeZone('+0000');
        try {
            $new_tz = new \DateTimeZone($config->getTimeZone());
            $timezone = $new_tz;
        } catch (\Exception $e) {
            // Well then the default is used.
        }
        // See if it is disallowed then.
        $date = new \DateTime('now', $timezone);
        return self::isDisallowedFromTime($date, $config);
    }

    public static function isDisallowedFromTime(\DateTime $date, Config $config)
    {
        $frame = $config->getTimeFrameDisallowed();
        if (empty($frame)) {
            return false;
        }
        $hour_parts = explode('-', $frame);
        $timezone = $date->getTimezone();
        $low_time_object = new \DateTime($hour_parts[0], $timezone);
        $high_time_object = new \DateTime($hour_parts[1], $timezone);
        // If the high object is lower than the low, we just add a whole day.
        // This is to accommodate timeframes like 23-01 or 08:00-00:00. So
        // passing midnight I guess.
        if ($low_time_object > $high_time_object) {
            $high_time_object->modify('+1 day');
        }
        if ($date->format('U') > $low_time_object->format('U') && $date->format('U') < $high_time_object->format('U')) {
            return true;
        }
        return false;
    }
}
