<?php


namespace AoC2018\Days;

class Day5 extends AbstractDay
{
    protected $title = 'Alchemical Reduction';

    protected function part1()
    {
        $polymer = $this->retractPolymer($this->readinput(), $this->generateReplacements());
        return strlen($polymer);
    }

    protected function generateReplacements()
    {
        $replacements = [];
        for ($i = 65; $i < 91; $i++) {
            $char = chr($i);
            $replacements[] = $char . strtolower($char);
            $replacements[] = strtolower($char) . $char;
        }
        return $replacements;
    }

    protected function retractPolymer($polymer, $replacements)
    {
        while (true) {
            $lengthLast = strlen($polymer);
            $polymer = str_replace($replacements, '', $polymer);
            if ($lengthLast == strlen($polymer)) {
                break;
            }
        }
        return $polymer;
    }

    protected function part2()
    {
        $replacements = [];
        for ($i = 65; $i < 91; $i++) {
            $char = chr($i);
            $replacements[] = [
                $char,
                strtolower($char)
            ];
        }

        $polymer = $this->readinput();
        $minimumCounts = [];
        foreach ($replacements as $replacement) {
            $workPolymer = $this->retractPolymer($polymer, $replacement);
            $workPolymer = $this->retractPolymer($workPolymer, $this->generateReplacements());
            $minimumCounts[] = strlen($workPolymer);
        }

        return min($minimumCounts);
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return trim(file_get_contents($file));
    }
}
