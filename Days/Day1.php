<?php


namespace AoC2018\Days;


class Day1 extends AbstractDay
{
    public function run()
    {
        $this->logTitle();

        $resultFrequency = $this->part1();
        $this->logLine("Result 1: $resultFrequency", self::$COLOR_GREEN);
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);

        $this->resetTimer();
        $repeatedFrequency = $this->part2();
        $this->logLine("Result 2: $repeatedFrequency", self::$COLOR_LIGHT_GREEN);
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);
    }

    protected function part1()
    {
        $frequencies = $this->readinput();

        return array_reduce(
            $frequencies,
            function ($carry, $value) {
                return $carry += (int)$value;
            }
        );
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
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }
}
