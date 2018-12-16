<?php


namespace AoC2018\Days;

use AoC2018\Days\Day15\Unit;

class Day15 extends AbstractDay
{
    protected $title = 'Beverage Bandits';

    protected function part1()
    {
        Unit::setCli($this);
        $test = 'test2';
        list($width, $height, $tileMap, $unitMap) = $this->loadMap($test);
        if ($test) {
            $this->renderMap($width, $height, $tileMap, $unitMap, false);
        }

        $round = 0;
        while (true) {
            if ($this->calculateRound($tileMap, $unitMap)) {
                break;
            }
            if ($test) {
                $this->renderMap($width, $height, $tileMap, $unitMap, true);
//                sleep(1);
            }
            $round += 1;

            $this->logText("round: $round");
            foreach (Unit::getList() as $unit) {
                $this->logText($unit->getType() . " ".$unit->getHP()." ");
//                $this->log($unit->getPosition());
            }

            readline_callback_handler_install('>', function() {});
            $char = stream_get_contents(STDIN, 1);
            readline_callback_handler_remove();
        }


        $cli = $this;
        $hpSum = array_reduce(Unit::getList(), function ($carry, $unit) use ($cli) {
            $cli->logText("HP: ".$unit->getHP());
            $carry += $unit->getHP();
            return $carry;
        });

        $this->logText("Round $round");
        $this->logText("HP $hpSum");

        return $round * $hpSum;
    }

    protected function part2()
    {
    }

    protected function readinput($name)
    {
        $file = $this->getInputFile($name);
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function loadMap($name)
    {
        $lines = $this->readinput($name);

        $width = strlen($lines[0]);
        $height = count($lines);

        $tileMap = [];
        $unitMap = [];

        $y = 0;
        foreach ($lines as $line) {
            $x = 0;
            foreach (str_split($line) as $char) {
                if ($char == 'G' || $char == 'E') {
                    $unitMap[$y][$x] = new Unit($char, $x, $y);
                    $tileMap[$y][$x] = 1;
                } elseif ($char == '#') {
                    $tileMap[$y][$x] = 1;
                } else {
                    $tileMap[$y][$x] = 0;
                }
                $x += 1;
            }
            $y += 1;
        }

        return [$width, $height, $tileMap, $unitMap];
    }

    protected function renderMap($width, $height, $tileMap, $unitMap, $erease = true)
    {
        if ($erease) {
            echo chr(27) . "[" . $height . "A";
        }

        for ($y = 0; $y < $height; $y++) {
            $line = '';
            for ($x = 0; $x < $width; $x++) {
                if (isset($unitMap[$y][$x])) {
                    $type = $unitMap[$y][$x]->getType();
                    $line .= $this->colorString($type, ($type == 'E') ? 'green' : 'red');
                } else {
                    $line .= ($tileMap[$y][$x] === 1) ? '#' : '.';
                }
            }
            $this->logText($line);
        }
    }

    protected function calculateRound(&$tileMap, &$unitMap)
    {
        /** @var Unit $unit */
        foreach (Unit::getList() as $unit) {
            // fight enemies
            $enemy = $unit->getAdjectedEnemy($unitMap);
            if ($enemy !== false) {
                if ($unit->fight($enemy)) {
                    // enemy destroyed
                    list($x, $y) = $enemy->getPosition();
                    $unitMap[$y][$x] = null;
                    $tileMap[$y][$x] = 0;
                    if (count(Unit::getList('G')) == 0 || count(Unit::getList('E')) == 0) {
                        return true;
                    }
                }
                continue;
            }

            // move if possible
            $newPosition = $unit->getNewPosition($tileMap);
            if ($newPosition !== false) {
                list($x, $y) = $unit->getPosition();
                $unitMap[$y][$x] = null;
                $tileMap[$y][$x] = 0;
                $unitMap[$newPosition[1]][$newPosition[0]] = $unit;
                $tileMap[$newPosition[1]][$newPosition[0]] = 1;
                $unit->setPosition($newPosition);
            }

            // fight enemies
            $enemy = $unit->getAdjectedEnemy($unitMap);
            if ($enemy !== false) {
                if ($unit->fight($enemy)) {
                    // enemy destroyed
                    list($x, $y) = $enemy->getPosition();
                    $unitMap[$y][$x] = null;
                    $tileMap[$y][$x] = 0;
                    if (count(Unit::getList('G')) == 0 || count(Unit::getList('E')) == 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
