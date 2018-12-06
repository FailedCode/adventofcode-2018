<?php


namespace AoC2018\Days;


class Day5 extends AbstractDay
{
    protected $title = '';

    protected function part1()
    {
        $replacements = [];
        for ($i = 65; $i < 91; $i++) {
            $char = chr($i);
            $replacements[] = $char . strtolower($char);
            $replacements[] = strtolower($char) . $char;
        }

        $polymer = $this->readinput();
        while (true) {
            $lengthLast = strlen($polymer);
            $polymer = str_replace($replacements, '', $polymer);
            if ($lengthLast == strlen($polymer)) {
                break;
            }
        }

        return $lengthLast;
    }

    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return trim(file_get_contents($file));
    }
}
