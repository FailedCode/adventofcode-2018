<?php


namespace AoC2018\Days;


class Day7 extends AbstractDay
{
    protected $title = 'The Sum of Its Parts';

    protected function part1()
    {
        $finishedSteps = [];
        $mustBeFinishedBefore = $this->getSteps();

        while (true) {
            $nextStep = $this->getNextStep($mustBeFinishedBefore);
            if ($nextStep === false) {
                break;
            }
            $finishedSteps[] = $nextStep;
            unset($mustBeFinishedBefore[$nextStep]);
            $mustBeFinishedBefore = $this->removePrerequisite($mustBeFinishedBefore, $nextStep);
        }
        return implode('', $finishedSteps);
    }

    protected function getNextStep($mustBeFinishedBefore)
    {
        $result = [];
        foreach ($mustBeFinishedBefore as $step => $prerequisite) {
            if (count($prerequisite) == 0) {
                $result[] = $step;
            }
        }
        if (count($result) == 0) {
            return false;
        }
        sort($result);
        return $result[0];
    }

    protected function removePrerequisite($mustBeFinishedBefore, $toRemove)
    {
        foreach ($mustBeFinishedBefore as $step => &$prerequisite) {
            if (in_array($toRemove, $prerequisite)) {
                $key = array_keys($prerequisite, $toRemove)[0];
                unset($prerequisite[$key]);
            }
        }
        return $mustBeFinishedBefore;
    }

    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function getSteps()
    {
        // key: character, value: array of needing completion first
        $steps = [];
        $lines = $this->readinput();
        foreach ($lines as  $line) {
            preg_match_all('~[Ss]tep\s([A-z])~', $line, $match);
            $prerequisite = $match[1][0];
            $step = $match[1][1];
            if (!isset($steps[$step])) $steps[$step] = [];
            if (!isset($steps[$prerequisite])) $steps[$prerequisite] = [];
            $steps[$step][] = $prerequisite;
        }
        return $steps;
    }

}
