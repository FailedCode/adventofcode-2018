<?php


namespace AoC2018\Days;


class Day1 extends AbstractDay
{
    public function run()
    {
        $this->logLine("Day 1", self::$COLOR_GREEN);

        $resultFrequency = $this->part1();
        $repeatedFrequency = $this->part2();

        $this->logLine("Result 1: $resultFrequency", self::$COLOR_GREEN);
        $this->logLine("Result 2: $repeatedFrequency", self::$COLOR_LIGHT_GREEN);
        $this->logLine("Time: ". $this->getDurationFormated(), self::$COLOR_YELLOW);

    }

    protected function part1()
    {
        $frequencies = $this->readinput();

        // Start with 0 and add every number
        $resultFrequency = 0;
        foreach ($frequencies as $frequency) {
            $resultFrequency += (int)$frequency;
        }
        return $resultFrequency;
    }

    protected function part2()
    {
        $frequencies = $this->readinput();
        $currentFrequency = 0;

        // record frequencies as array
        $repeatedFrequencies = [$currentFrequency => 1];

        // loop over the frequency changes endlessly
        while (true) {
            foreach ($frequencies as $frequency) {
                $currentFrequency += (int)$frequency;

                // is the frequency already known?
                if (isset($repeatedFrequencies[$currentFrequency])) {
                    // it occurred twice then!
                    return $currentFrequency;
                } else {
                    // remember this frequency
                    $repeatedFrequencies[$currentFrequency] = 1;
                }
            }
        }
    }

    protected function readinput()
    {
        $file = $this->config['input_path'] . 'day1.txt';
        return explode("\n", file_get_contents($file));
    }
}
