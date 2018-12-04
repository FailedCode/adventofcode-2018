<?php


namespace AoC2018\Days;


use AoC2018\Utility\Cli;

abstract class AbstractDay extends Cli
{

    protected $title = '';

    protected $resultCount = 0;

    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->config['input_path'] = __dir__ . "/Input/";
        $this->config['testvalue_path'] = __dir__ . "/testvalues.php";

        // self:: refers to the scope at the point of definition not at the point of execution
        // https://stackoverflow.com/questions/151969/when-to-use-self-over-this
        // self::class will result in
        //   Dynamic class names are not allowed in compile-time ::class fetch in <file>
        if (preg_match('~Day(\d+)~', static::class, $match)) {
            $this->config['day_nr'] = (int)$match[1];
        }
        if (!isset($this->config['part'])) {
            $this->config['part'] = 0;
        }
    }

    public function run()
    {
        $this->logTitle();
        if ($this->config['part'] == 0 || $this->config['part'] == 1) {
            $this->logResult($this->part1());
        }
        if ($this->config['part'] == 0 || $this->config['part'] == 2) {
            $this->logResult($this->part2());
        }
    }

    public function runTest()
    {
        if (!file_exists($this->config['testvalue_path'])) {
            $this->logLine("No test values available!", self::$COLOR_YELLOW);
            exit(0);
        }
        $testvalues = require($this->config['testvalue_path']);
        $excpect1 = $testvalues[$this->config['day_nr']][0];
        $excpect2 = $testvalues[$this->config['day_nr']][1];

        // dont show something red when testing
        $this->logLine("Day {$this->config['day_nr']}: {$this->title}", self::$COLOR_LIGHT_BLUE);

        $this->runTestOnPart(1, $excpect1);
        $this->runTestOnPart(2, $excpect2);
    }

    protected function runTestOnPart($part, $excpect)
    {
        if ($this->config['part'] != 0 && $this->config['part'] != $part) {
            return;
        }
        if (empty($excpect)) {
            $this->logLine("Part $part: Result not yet know!", self::$COLOR_YELLOW);
        } else {
            $method = "part$part";
            $result = (string)$this->$method();
            if ($excpect === $result) {
                $this->logLine("Part $part: OK", self::$COLOR_LIGHT_GREEN);
            } else {
                $this->logLine("Part $part: FAILED", self::$COLOR_RED);
                $this->logLine("Expected \"{$excpect}\" but got \"$result\" instead!", self::$COLOR_RED);
            }
        }
    }

    protected function logTitle()
    {
        $day = $this->config['day_nr'];
        $this->logLine("Day $day: {$this->title}", (($day % 2) ? self::$COLOR_GREEN : self::$COLOR_LIGHT_RED));
    }

    protected function logTime()
    {
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);
        $this->resetTimer();
    }

    protected function logResult($result)
    {
        $this->resultCount += 1;
        $this->logLine("Result {$this->resultCount}: " . $this->colorString($result, self::$COLOR_BLUE));
        $this->logTime();
    }

    /**
     * @return string
     */
    protected function getInputFile()
    {
        $fileName = 'day' . $this->config['day_nr'] . '.txt';
        $filePath = $this->config['input_path'] . $fileName;
        if (!file_exists($filePath)) {
            $this->logLine("Input File '$fileName' does not exist", self::$COLOR_YELLOW);

            $input = $this->downloadPuzzleInput($this->config['day_nr']);
            if (!$input) {
                $this->logLine("Download the input file by hand or store your session Cookie in an .env file!", self::$COLOR_LIGHT_PURPLE);
                exit(0);
            }
            $this->logLine("Saved downloaded input as $fileName", self::$COLOR_LIGHT_GREEN);
            file_put_contents($filePath, $input);
        }
        return $filePath;
    }

    /**
     * @param $dayNr
     * @return bool|string
     */
    protected function downloadPuzzleInput($dayNr)
    {
        if (!isset($this->config["ENV_SESSION"])) {
            return false;
        }

        $curl = \curl_init();
        $options = [
            CURLOPT_URL => "https://adventofcode.com/2018/day/$dayNr/input",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIE => "session=".$this->config["ENV_SESSION"],
        ];
        \curl_setopt_array($curl, $options);
        $result = \curl_exec($curl);
        $err = \curl_errno($curl);
        $errmsg = \curl_error($curl);
        \curl_close($curl);

        if ($err) {
            $this->logLine("Error downloading input for day $dayNr", self::$COLOR_RED);
            $this->logLine($errmsg, self::$COLOR_RED);
            return false;
        }
        return $result;
    }
}
