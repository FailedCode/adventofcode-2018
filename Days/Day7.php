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
        $taskTime = 60;
        $workers = [
            0 => ['task' => '', 'time' => 0],
            1 => ['task' => '', 'time' => 0],
            2 => ['task' => '', 'time' => 0],
            3 => ['task' => '', 'time' => 0],
            4 => ['task' => '', 'time' => 0],
        ];
        // $mustBeFinishedBefore = ['A' => ['C'], 'C' => [], 'F' => ['C'], 'B' => ['A'], 'E' => ['D'], 'D' => ['B']];
        $mustBeFinishedBefore = $this->getSteps();
        $time = 0;
        while (true) {

            $freeWorkers = [];
            foreach ($workers as $id => &$worker) {
                if ($worker['task'] === '') {
                    $freeWorkers[] = $id;
                    continue;
                }

                // the workers work on the task...
                $worker['time'] -= 1;
//                $this->logLine("[$time] worker $id works on {$worker['task']} {$worker['time']}s left");
                if ($worker['time'] == 0) {
                    $step = $worker['task'];
                    $worker['task'] = '';
                    // the task is done and no longer blocks following tasks
                    $mustBeFinishedBefore = $this->removePrerequisite($mustBeFinishedBefore, $step);
                    $freeWorkers[] = $id;
//                    $this->logLine("[$time] worker $id done with $step");
//                    $this->logLine(implode('', $finishedSteps));
                }
            }

            while (count($freeWorkers) > 0) {
                $nextStep = $this->getNextStep($mustBeFinishedBefore);
                if ($nextStep === false) {
                    //$this->logLine("[$time] No task available");
                    break;
                }
                // remove the task, so the next worker doesn't get the same
                unset($mustBeFinishedBefore[$nextStep]);
                $id = array_shift($freeWorkers);
                $workers[$id]['task'] = $nextStep;
                $workers[$id]['time'] = $taskTime + ord($nextStep) - 64; // -64 => A = 1
//                $this->logLine("[$time] worker $id starts with $nextStep, will take {$workers[$id]['time']}s");
            }

//            $this->logLine("[$time] Workers: {$workers[0]['task']}|{$workers[1]['task']}|{$workers[2]['task']}|{$workers[3]['task']}|{$workers[4]['task']}");

            // If all workers are idle, there is nothing more to do
            if (count($freeWorkers) == 5) {
//                $this->logLine("[$time] all workers free: END");
                break;
            }

            $time += 1;
        }

        return $time;
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
