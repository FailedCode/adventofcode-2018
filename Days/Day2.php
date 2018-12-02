<?php


namespace AoC2018\Days;


class Day2 extends AbstractDay
{
    protected $title = 'Inventory Management System';

    public function run()
    {
        $this->logTitle();
        $this->logResult($this->part1());
        $this->logResult($this->part2());
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
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }
}
