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

    /**
     * Add Newline to output and display in a color
     *
     * @param $data
     * @param null $color
     */
    public function logLine($data, $color = null)
    {
        $text = $this->formatLog($data);
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
     * For long running processes to show some progress
     *
     * modes:
     *   progress
     *      circles through symbols to show process runs
     *   percent
     *      displays percent
     *      66%
     *   percent-bar-small
     *      visual percent bar
     *      [=====     ] 50%
     *   percent-bar-big
     *      visual percent bar
     *      [=====                                                                                               ] 5%
     *   absolute
     *      Display absolute units
     *      64/100
     *      64...
     *
     *   reset
     *      Removes the last progress output
     *   reset-with-alert
     *      Removes the last progress output and plays notification sound of console
     *
     * @param string $modus
     * @param int|string $currentValue
     * @param int $maxValue
     */
    public function logProgress($modus = 'simple', $currentValue = 0, $maxValue = 0)
    {
        static $lastOutput = '';
        static $lastTime = 0;

        // Do not update more than once a second unless its a reset
        if ($lastTime > 0 && $lastTime == time() && strpos($modus, 'reset') === false) {
            return;
        }

        // remove the last output
        if (strlen($lastOutput) > 0) {
            // go back
            echo str_repeat(chr(8), strlen($lastOutput));
            // overwrite with spaces
            echo str_repeat(' ', strlen($lastOutput));
            // go back again
            echo str_repeat(chr(8), strlen($lastOutput));
        }

        switch ($modus) {
            // the last output was removed, clean end
            case 'reset':
                $lastTime = 0;
                $lastOutput = '';
                return;
            // cause the console to beep/system alert sound to play
            case 'reset-with-alert':
                print "\x07";
                $lastTime = 0;
                $lastOutput = '';
                return;

            case 'progress':
            default:
                $symbols = ['-', '\\', '|', '/'];
                $key = array_search($lastOutput, $symbols);
                if ($key === false) {
                    $key = 0;
                } else {
                    $key = ($key + 1) % count($symbols);
                }
                $lastOutput = $symbols[$key];
                break;

            case 'progress-value':
                $symbols = ['-', '\\', '|', '/'];
                $key = array_search(substr($lastOutput, 0, 1), $symbols);
                if ($key === false) {
                    $key = 0;
                } else {
                    $key = ($key + 1) % count($symbols);
                }
                $lastOutput = $symbols[$key] . " [{$currentValue}]";
                break;

            // 66%
            case 'percent':
                if ($maxValue <= 0) {
                    $lastOutput = "?!%";
                } else {
                    $lastOutput = (int)(100 * $currentValue / $maxValue) . '%';
                }
                break;

            // [=====     ] 50%
            case 'percent-bar-small':
                if ($maxValue <= 0) {
                    $lastOutput = "?!%";
                } else {
                    $p = (int)(100 * $currentValue / $maxValue);
                    $f = (int)$p / 10;
                    if ($p / 10 < 1) {
                        $filled = '>';
                    } else {
                        $filled = str_repeat('=', $f);
                    }
                    $left = str_repeat(' ', (10 - $f));
                    $lastOutput = "[{$filled}{$left}] $p%";
                }
                break;

            // [=====                                                                                               ] 5%
            case 'percent-bar-big':
                if ($maxValue <= 0) {
                    $lastOutput = "?!%";
                } else {
                    $p = (int)(100 * $currentValue / $maxValue);
                    $filled = str_repeat('=', $p);
                    $left = str_repeat(' ', (100 - $p));
                    $lastOutput = "[{$filled}{$left}] $p%";
                }
                break;

            // 64/100
            // 64...
            case 'absolute':
                if ($maxValue > 0) {
                    $lastOutput = "$currentValue/$maxValue";
                } else {
                    $lastOutput = "$currentValue...";
                }
        }
        $lastTime = time();
        echo $lastOutput;
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
     * Translate short codes to color names
     *
     * @param string $color
     * @return string
     */
    protected function getColorAlias($color = '')
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

    /**
     * Get the Terminal Color code for a color name
     * or false if unknown color
     *
     * @param $color
     * @return bool|mixed
     */
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
