<?php


namespace AoC2018\Days;


class Day2 extends AbstractDay
{
    public function run()
    {
        $this->logLine("Day 2", self::$COLOR_BLUE);

        $part1 = $this->part1();
        $this->logLine("Result 1: $part1", self::$COLOR_GREEN);
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);

        $this->resetTimer();
        $part2 = $this->part2();
        $this->logLine("Result 2: $part2", self::$COLOR_LIGHT_GREEN);
        $this->logLine("Time: " . $this->getDurationFormated(), self::$COLOR_YELLOW);
    }

    protected function part1()
    {
        $inputList = $this->readinput();
        $twice = 0;
        $thrice = 0;
        foreach ($inputList as $id) {
            $chars = str_split($id);
            $charCount = [];
            foreach ($chars as $char) {
                if (isset($charCount[$char])) {
                    $charCount[$char] += 1;
                } else {
                    $charCount[$char] = 1;
                }
            }

            if (array_search(2, $charCount)) {
                $twice += 1;
            }
            if (array_search(3, $charCount)) {
                $thrice += 1;
            }
        }

        return $twice * $thrice;
    }

    protected function part2()
    {
        $inputList = $this->readinput();
        foreach ($inputList as $id1) {
            foreach ($inputList as $id2) {
                if ($id1 == $id2) {
                    continue;
                }

                $common = $this->differenceOnceStrings($id1, $id2);
                if (is_array($common))
                {
                    return implode('', $common);
                }
            }
        }
    }

    protected function differenceOnceStrings($a, $b)
    {
        $commonChars = [];
        $diffs = 0;
        $aChars = str_split($a);
        $bChars = str_split($b);

        for ($i = 0; $i < count($aChars); $i++) {
            if ($aChars[$i] == $bChars[$i]) {
                $commonChars[] = $aChars[$i];
            } else {
                $diffs += 1;
                if ($diffs > 1) {
                    return false;
                }
            }
        }

        if ($diffs === 1) {
            return $commonChars;
        }
        return false;
    }

    protected function readinput()
    {
        $file = $this->config['input_path'] . 'day2.txt';
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }
}
