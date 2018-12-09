<?php


namespace AoC2018\Days;

use AoC2018\Days\Day9\Marble;


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
        // prevent segfault
        // see https://bugs.php.net/bug.php?id=72411
        gc_disable();

        $players = array_fill(0, $playerNumber, 0);
        $currentMarble = new Marble(0);
        $currentPlayer = 0;

        for ($i = 1; $i < $maxMarbles + 1; $i++) {
            if (($i % 23) == 0) {
                $currentMarble = $currentMarble->getPrevious(7);
                $players[$currentPlayer] += $i + $currentMarble->getValue();
                $currentMarble = $currentMarble->remove();
            } else {
                $currentMarble = $currentMarble->getNext(1);
                $currentMarble = $currentMarble->insertAfter($i);
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
        return ['players' => (int)$matches[1], 'marbles' => (int)$matches[2]];
    }
}
