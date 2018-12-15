<?php

namespace AoC2018\Utility;

class Cli
{
    use Traits\LogTrait;
    use Traits\TimerTrait;

    /**
     * Configuration
     * @var array
     */
    protected $config = [];

    /**
     * Modify default config, connect to databases
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        // Instead of Notices, we throw exceptions
        set_error_handler(function ($severity, $message, $filename, $lineno) {
            if (error_reporting() == 0) {
                return;
            }
            if (error_reporting() & $severity) {
                throw new \ErrorException($message, 0, $severity, $filename, $lineno);
            }
        });

        $this->resetTimer();
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }

        $envFile = __dir__ . '/../.env';
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
}
