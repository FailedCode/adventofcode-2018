#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

// remove script file name
array_shift($argv);

foreach ($argv as $i => $parameter)
{
    if (is_numeric($parameter)) {
        $class = "AoC2018\Days\Day$parameter";
        $day = new $class();
        $day->run();
    }
}
