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

    /**
     * Display text with [<color>] interpreted
     *
     * @param $text
     */
    public function logText($text)
    {
        $lines = explode("\n", $text);
        $output = [];
        $color = false;

        foreach ($lines as $line) {
            $pattern = '~(\[[^]]*\])~';
            $parts = preg_split($pattern, $line, -1, PREG_SPLIT_DELIM_CAPTURE);

            foreach ($parts as $part) {
                if (preg_match($pattern, $part)) {
                    $color = $this->getColorAlias(str_replace(['[', ']'], '', $part));
                    continue;
                }
                $output[] = $this->colorString($part, $color);
            }
            $output[] = "\n";
        }
        $this->log(implode("", $output));
    }

    public function logLine($data, $color = null)
    {
        $text = $this->formatLog($data);
        $this->log("$text\n", $color);
    }

    public function logLines($text)
    {
        if (is_array($text)) {
            foreach ($text as $line => $color) {
                $this->logLine($line, $color);
            }
            return;
        }
        $this->log($text);
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

    protected function getColorAlias($color)
    {
        $color = strtolower($color);
        $aliases = [
            'r' => 'red',
            'g' => 'green',
            'b' => 'blue',
            'lr' => 'light_red',
            'lg' => 'light_green',
            'lb' => 'light_blue',
            'lc' => 'light_cyan',
            'lp' => 'light_purple',
            'dg' => 'dark_gray',
            'p' => 'purple',
            'y' => 'yellow',
            'w' => 'white',
            'x' => 'black',
            '' => 'light_gray',
        ];
        if (isset($aliases[$color])) {
            return $aliases[$color];
        }
        return $color;
    }

    protected function getColor($color)
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
        if (isset($colors[$color])) {
            return $colors[$color];
        }
        return false;
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
        $color = $this->getColor($color);
        if (!$color) {
            return $str;
        }
        return "\033[{$color}m{$str}\033[0m";

    }
}
