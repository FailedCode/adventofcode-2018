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
            $this->logLine("Input File '$fileName' does not exist", self::$COLOR_RED);
            exit(0);
            //todo: fetch the file
            // https://adventofcode.com/2018/day/<DAY>/input
            // Personal Cookie needed
        }
        return $filePath;
    }
}
