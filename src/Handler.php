<?php

namespace Violinist\TimeFrameHandler;

use Violinist\Config\Config;

class Handler
{

    public static function createFromConfig(Config $config) 
    {
        $frame = $config->getTimeFrameDisallowed();
        if (empty($frame)) {
            return;
        }
    }
}
