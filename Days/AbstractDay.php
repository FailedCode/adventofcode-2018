<?php


namespace AoC2018\Days;

use AoC2018\Utility\Cli;

abstract class AbstractDay extends Cli
{

    protected $title = '';

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
            $this->logResult(1, $this->part1());
        }
        if ($this->config['part'] == 0 || $this->config['part'] == 2) {
            $this->logResult(2, $this->part2());
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
        $this->logText("[LB]Day [W]{$this->config['day_nr']}[LB]: [P]{$this->title}");

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
            return;
        }

        $method = "part$part";
        $this->resetTimer();
        $result = (string)$this->$method();
        if ($excpect === $result) {
            $this->logText("[G]Part $part: [LG]OK [Y]({$this->getDurationFormated()})");
        } else {
            $this->logText("[R]Part $part: [LR]FAILED [Y]({$this->getDurationFormated()})");
            $this->logText("[R]Expected \"[B]{$excpect}[R]\" but got \"[BROWN]{$result}[R]\" instead!");
        }
    }

    protected function logTitle()
    {
        $this->logLine(
            "Day {$this->config['day_nr']}: {$this->title}",
            (($this->config['day_nr'] % 2) ? self::$COLOR_GREEN : self::$COLOR_LIGHT_RED)
        );
    }

    protected function logTime()
    {
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);
        $this->resetTimer();
    }

    protected function logResult($part, $result)
    {
        $this->logLine("Result $part: " . $this->colorString($result, self::$COLOR_BLUE));
        $this->logTime();
    }

    /**
     * A nickname can be used to load other peoples input for test/debugging
     *
     * @param string $nickname
     * @return string
     */
    protected function getInputFile($nickname = '')
    {
        $fileName = implode('', ['day', $this->config['day_nr'], (!empty($nickname) ? "-$nickname" : ''), '.txt']);
        $filePath = $this->config['input_path'] . (!empty($nickname) ? 'Others/' : '') . $fileName;
        if (!file_exists($filePath)) {
            $this->logLine("Input File '$filePath' does not exist", self::$COLOR_YELLOW);

            if ($nickname === '') {
                $input = $this->downloadPuzzleInput($this->config['day_nr']);
                if (!$input) {
                    $this->LogText("[LP]Download the input file by hand or store your session Cookie in an .env file!");
                    exit(0);
                }
                $this->logLine("Saved downloaded input as $fileName", self::$COLOR_LIGHT_GREEN);
                file_put_contents($filePath, $input);
            }
            if (!file_exists($filePath)) {
                exit(0);
            }
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
