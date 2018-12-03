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
    protected $time = 0.0;

    /**
     * Modify default config, connect to databases
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        $this->resetTimer();
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }

        $envFile = __dir__ .  '/../.env';
        if (file_exists($envFile)) {
            $lines = array_filter(explode("\n", file_get_contents($envFile)), 'strlen');
            foreach ($lines as $line) {
                // skip comments
                if (preg_match('~^\s*#~', $line)) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    $var = '';
                    $value = '';
                    list($var, $value) = explode('=', $line, 2);
                    $var = strtoupper(trim($var));
                    if (strlen($var) > 1) {
                        $this->config["ENV_$var"] = $value;
                    }
                }
            }
        }
    }

    /**
     * Save the current time
     */
    public function resetTimer()
    {
        $this->time = microtime(true);
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return microtime(true) - $this->time;
    }

    /**
     * @param null|float $duration
     * @return string
     */
    public function getDurationFormated($duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->getDuration();
        } else {
            $duration = (float)$duration;
        }
        if ($duration < 0.01) {
            return (int)($duration * 1000000) . ' µs';
        }if ($duration < 1) {
            return (int)($duration * 1000) . ' ms';
        }
        if ($duration < 3) {
            return number_format($duration, 2) . ' s';
        }
        return (int)$duration . ' s';
    }
}
