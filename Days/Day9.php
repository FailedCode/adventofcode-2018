<?php


namespace AoC2018\Days;


class Day9 extends AbstractDay
{
    protected $title = 'Marble Mania';

    protected function part1()
    {
        $input = $this->readinput();
        $playerNumber = $input['players'];
        $maxMarbles = $input['marbles'];

        return $this->playMarbleGame($playerNumber, $maxMarbles);
    }

    protected function playMarbleGame($playerNumber, $maxMarbles)
    {
        $players = array_fill(0, $playerNumber, 0);
        $marbles = [0 => 0];
        $currentMarble = 0;
        $currentPlayer = 0;

        for ($i = 1; $i < $maxMarbles + 1; $i++) {
            $marbleCount = count($marbles);

            if (($i % 23) == 0) {
                $currentMarble = ($currentMarble - 7 + $marbleCount) % $marbleCount;
                $players[$currentPlayer] += $i + $marbles[$currentMarble];

                array_splice($marbles, $currentMarble, 1);

//                $marble = $marbles[$currentMarble];
//                $this->logText(str_replace(" $marble ", $this->colorString(" ($marble) ", 'blue'), implode(' ', $marbles) . ' '));
            } else {
                $currentMarble = ($currentMarble + 1) % $marbleCount;
                array_splice($marbles, $currentMarble + 1, 0, $i);
                $currentMarble = array_keys($marbles, $i)[0];
//                $this->logText(str_replace(" $i ", $this->colorString(" ($i) ", 'blue'), (implode(' ', $marbles) . ' ')));

            }
            $currentPlayer = ($currentPlayer + 1) % $playerNumber;
            $this->logProgress('percent-bar-big', $i, $maxMarbles+1);
        }
        $this->logProgress('reset');
        return max($players);
    }

    protected function part2()
    {
        $input = $this->readinput();
        $playerNumber = $input['players'];
        $maxMarbles = $input['marbles'];

        return $this->playMarbleGame($playerNumber, $maxMarbles * 100);
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        preg_match('~(\d+).*?(\d+).*~', file_get_contents($file), $matches);
        return ['players' => $matches[1], 'marbles' => $matches[2]];
    }
}
