<?php

namespace AoC2018\Utility;


class Cli
{
    use Traits\LogTrait;

    /**
     * Configuration
     * @var array
     */
    protected $config = [];

    /**
     * Measure time used
     * @var int
     */
    protected $time = 0;

    /**
     * Modify default config, connect to databases
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        $this->time = time();
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    public function getDuration()
    {
        return time() - $this->time;
    }
}
