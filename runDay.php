#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

// remove script file name
array_shift($argv);
$cli = new \AoC2018\Utility\Cli();

if (array_search('--help', $argv) !== false) {
    $cli->logLines([
        "Usage: " => $cli::$COLOR_YELLOW,
        "./runDay.php 1 => run day 1" => $cli::$COLOR_WHITE,
        "./runDay.php 1 2 3 => run days 1, 2 and 3" => $cli::$COLOR_WHITE,
        "./runDay.php 2p1 => run day 2 only part 1" => $cli::$COLOR_WHITE,
        "./runDay.php 2p2 => run day 2 only part 2" => $cli::$COLOR_WHITE,
    ]);
    exit(0);
}

$testrun = false;
if (array_search('--test', $argv) !== false) {
    $testrun = true;
}

foreach ($argv as $i => $parameter) {
    $dayNr = 0;
    $partNr = 0;

    if (strpos($parameter, 'p') !== false) {
        if (preg_match('~(\d+)p(\d+)~', $parameter, $match)) {
            $dayNr = (int)$match[1];
            $partNr = (int)$match[2];
        } else {
            $dayNr = (int)$parameter;
        }
    } elseif (is_numeric($parameter)) {
        $dayNr = (int)$parameter;
    }

    if ($dayNr > 0) {
        $class = "AoC2018\Days\Day$dayNr";

        if (!file_exists("Days/Day$dayNr.php")) {
            $cli->logLine("$class not (yet) implemented", $cli::$COLOR_RED);
            continue;
        }

        $day = new $class(['part' => $partNr]);

        if ($testrun) {
            $day->runTest();
        } else {
            $day->run();
        }
    }

}
