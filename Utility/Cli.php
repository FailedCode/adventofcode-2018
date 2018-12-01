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
     * @var float
     */
    protected $time = 0;

    /**
     * Modify default config, connect to databases
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        $this->time = microtime(true);
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return microtime(true) - $this->time;
    }

    /**
     * @return string
     */
    public function getDurationFormated()
    {
        $duration = $this->getDuration();
        if ($duration < 0.001) {
            return (int)($duration * 1000000) . ' µs';
        }if ($duration < 0.01) {
            return (int)($duration * 1000) . ' ms';
        }
        if ($duration < 3) {
            return number_format($duration, 2) . ' s';
        }
        return (int)$duration . ' s';
    }
}
