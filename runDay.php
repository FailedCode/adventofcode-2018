#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

// remove script file name
array_shift($argv);
$cli = new \AoC2018\Utility\Cli();

if (array_search('--help', $argv) !== false) {
    $cli->logText([
            "[Y]Usage:",
            "[W]./runDay.php [B]1[] => run day 1",
            "[W]./runDay.php [B]1 2 3[] => run days 1, 2 and 3",
            "[W]./runDay.php [B]2p1[] => run day 2 only part 1",
            "[W]./runDay.php [B]2p2[] => run day 2 only part 2",
            "[W]./runDay.php [B]2[G] --test[] => test day 2 results",
            "[W]./runDay.php [G]--psr2-check[] => check code for PSR-2 violations",
    ]);
    exit(0);
}

if (array_search('--psr2-check', $argv) !== false) {
    exec("vendor/bin/phpcs --colors --standard=PSR2 Utility/ Days/ runDay.php", $output, $result);
    foreach ($output as $line) {
        if (preg_match('~FILE: (.*)~', $line, $match)) {
            $line = str_replace(__dir__, '', $line);
        }
        $cli->logLine($line);
    }
    if ($result === 0) {
        $cli->logText("[LG]OK");
    }
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
            $cli->logText("[Y]{$class}[LR] not (yet) implemented");
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
