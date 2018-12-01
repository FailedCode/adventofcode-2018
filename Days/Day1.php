<?php


namespace AoC2018\Days;


class Day1 extends AbstractDay
{
    public function run()
    {
        $this->logLine("Day 1", self::$COLOR_GREEN);

        // Start with 0 and add every number
        $resultFrequency = 0;
        $frequencies = $this->readinput();
        foreach ($frequencies as $frequency) {
            $resultFrequency += (int)$frequency;
        }

        $this->logLine("Result: $resultFrequency", self::$COLOR_GREEN);
        $this->logLine("Time: ". $this->getDurationFormated(), self::$COLOR_YELLOW);
    }

    protected function readinput()
    {
        $file = $this->config['input_path'] . 'day1.txt';
        return explode("\n", file_get_contents($file));
    }
}
