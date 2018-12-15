<?php


namespace AoC2018\Days;

class Day1 extends AbstractDay
{
    protected $title = 'Chronal Calibration';

    protected function part1()
    {
        $frequencies = $this->readinput();

        return array_reduce(
            $frequencies,
            function ($carry, $value) {
                $carry += (int)$value;
                return $carry;
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
