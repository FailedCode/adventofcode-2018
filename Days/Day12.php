<?php


namespace AoC2018\Days;


class Day12 extends AbstractDay
{
    protected $title = 'Subterranean Sustainability';

    protected function part1()
    {
        $input = $this->readinput();
        $pots = $input['init'];
        $rules = $input['rules'];

        $this->logText("0 ) " . array_reduce($pots, function ($carry, $value) {
                $carry .= $value ? '#' : '.';
                return $carry;
            }));
        for ($i = 1; $i < 21; $i++) {
            $pots = $this->calculateNewGeneration($pots, $rules);
            $this->logText(str_pad($i, 2) .") " . array_reduce($pots, function ($carry, $value) {
                    $carry .= $value ? '#' : '.';
                    return $carry;
                }));
        }

        return $this->calculatePotSum($pots);
    }

    protected function calculateNewGeneration($pots, $rules)
    {
        $firstFilledPot = 0;
        $lastFilledPot = 0;
        foreach ($pots as $key => $value) {
            if ($value) {
                $firstFilledPot = $key;
                break;
            }
        }
        foreach (array_reverse($pots, true) as $key => $value) {
            if ($value) {
                $lastFilledPot = $key;
                break;
            }
        }

        $newPots = [];
        for ($i = $firstFilledPot - 2; $i < $lastFilledPot + 3; $i++) {
            foreach ($rules as $rule) {
                $ruleMatched = true;

                for ($n = -2; $n < 3; $n++) {
                    $hasPlant = isset($pots[$i + $n]) ? $pots[$i + $n] : 0;
                    if ($hasPlant != $rule[$n + 2]) {
                        $ruleMatched = false;
                        break;
                    }
                }

                if ($ruleMatched) {
                    $newPots[$i] = 1;
                    break;
                }
            }
            if (!isset($newPots[$i])) {
                $newPots[$i] = 0;
            }
        }

        return $newPots;
    }

    protected function calculatePotSum($pots)
    {
        $sum = 0;
        foreach ($pots as $key => $value) {
            if ($value === 1) {
                // why is +4 necessary???
                $sum += $key + 4;
            }
        }
        return $sum;
    }

    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        $result = [
            'init' => [],
            'rules' => [],
        ];
        $data = array_filter(explode("\n", file_get_contents($file)), 'strlen');
        $inital = str_split(str_replace('initial state: ', '', array_shift($data)));
        $i = -4;
        foreach ($inital as $element) {
            $result['init'][$i] = ($element === '#') ? 1 : 0;
            $i += 1;
        }

        foreach ($data as $line) {
            list($p, $r) = explode(' => ', $line);
            $r = ($r === '#') ? 1 : 0;
            if (!$r) {
                // I only need to know when plants grow
                continue;
            }
            $pattern = array_map(function ($element) {
                return ($element === '#') ? 1 : 0;
            }, str_split($p));
            $result['rules'][] = $pattern;
        }

        return $result;
    }
}
