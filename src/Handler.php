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
        $low_time_object = new \DateTime($hour_parts[0], $timezone);
        $high_time_object = new \DateTime($hour_parts[1], $timezone);
        if ($date->format('U') > $low_time_object->format('U') && $date->format('U') < $high_time_object->format('U')) {
            return true;
        }
        return false;
    }
}
