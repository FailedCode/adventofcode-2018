<?php

namespace AoC2018\Utility\Traits;

trait LogTrait
{
    public static $COLOR_BLACK = 'black';
    public static $COLOR_DARK_GRAY = 'dark_gray';
    public static $COLOR_BLUE = 'blue';
    public static $COLOR_LIGHT_BLUE = 'light_blue';
    public static $COLOR_GREEN = 'green';
    public static $COLOR_LIGHT_GREEN = 'light_green';
    public static $COLOR_CYAN = 'cyan';
    public static $COLOR_LIGHT_CYAN = 'light_cyan';
    public static $COLOR_RED = 'red';
    public static $COLOR_LIGHT_RED = 'light_red';
    public static $COLOR_PURPLE = 'purple';
    public static $COLOR_LIGHT_PURPLE = 'light_purple';
    public static $COLOR_BROWN = 'brown';
    public static $COLOR_YELLOW = 'yellow';
    public static $COLOR_LIGHT_GRAY = 'light_gray';
    public static $COLOR_WHITE = 'white';

    public function logLine($text = '', $color = null)
    {
        $this->log("$text\n", $color);
    }

    /**
     * Output to console
     *
     * @param mixed $data
     * @param null|string $color
     */
    public function log($data, $color = null)
    {
        $output = $this->formatLog($data);
        if ($color) {
            $output = $this->colorString($output, $color);
        }

        echo "$output";
    }

    /**
     * Ensure the data is printable
     *
     * @param mixed $data
     * @return string
     */
    public function formatLog($data)
    {
        if (is_string($data)) {
            return $data;
        }
        if (is_null($data)) {
            return "[NULL]";
        }
        if (is_bool($data)) {
            if ($data) {
                return "[TRUE]";
            } else {
                return "[FALSE]";
            }
        }
        if (is_array($data)) {
            return print_r($data, true);
        }
        if (is_object($data)) {
            return gettype($data);
        }

        return (string)$data;
    }

    /**
     * Wrap a string in color codes for unix consoles
     *
     * @param string $str
     * @param string $color
     * @return string
     */
    public function colorString($str, $color = 'white')
    {
        $colors = [
            'black' => '0;30',
            'dark_gray' => '1;30',
            'blue' => '0;34',
            'light_blue' => '1;34',
            'green' => '0;32',
            'light_green' => '1;32',
            'cyan' => '0;36',
            'light_cyan' => '1;36',
            'red' => '0;31',
            'light_red' => '1;31',
            'purple' => '0;35',
            'light_purple' => '1;35',
            'brown' => '0;33',
            'yellow' => '1;33',
            'light_gray' => '0;37',
            'white' => '1;37',
        ];
        if (!isset($colors[$color])) {
            return $str;
        }
        return "\033[${colors[$color]}m$str\033[0m";
    }

}
