<?php


namespace AoC2018\Days;


use AoC2018\Utility\Cli;

abstract class AbstractDay extends Cli
{

    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->config['input_path'] = __dir__ . "/Input/";
    }

    abstract public function run();
}
