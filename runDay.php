#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

// remove script file name
array_shift($argv);

foreach ($argv as $i => $parameter) {
    if (is_numeric($parameter)) {
        $class = "AoC2018\Days\Day$parameter";

        if (!file_exists("Days/Day$parameter.php")) {
            $cli = new \AoC2018\Utility\Cli();
            $cli->logLine("$class not (yet) implemented", $cli::$COLOR_RED);
            continue;
        }

        $day = new $class();
        $day->run();
    }
}
