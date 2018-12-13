<?php


namespace AoC2018\Days;

use AoC2018\Days\Day13\Cart;

class Day13 extends AbstractDay
{
    protected $title = 'Mine Cart Madness';

    protected function part1()
    {
        $isTest = false;
        list($tracks, $width, $height, $carts) = $this->buildTracks($isTest);

        $collision = false;
        $i = 0;
        while ($collision === false) {
            if ($isTest) {
                $this->drawTrack($tracks, $width, $height, $carts, $i>0);
                sleep(1);
            }
            $collision = $this->moveCarts($tracks, $width, $height, $carts);
            $i += 1;
        }

        return implode(',', $collision);
    }

    protected function drawTrack($tracks, $width, $height, $carts, $erease=true)
    {
        if ($erease) {
            echo chr(27) . "[" . $height ."A";
        }

        for ($y = 0; $y < $height; $y++) {
            $line = '';
            for ($x = 0; $x < $width; $x++) {

                if (!is_null($carts[$y][$x])) {
                    $line .= $this->colorString($carts[$y][$x]->draw(), 'yellow');
                } else {
                    $line .= $tracks[$y][$x];
                }

            }
            $this->logLine($line);
        }
    }

    protected function moveCarts(&$tracks, $width, $height, &$carts)
    {
        Cart::resetMoves();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {

                if (is_null($carts[$y][$x])) {
                    continue;
                }
                /** @var Cart $cart */
                $cart = $carts[$y][$x];
                if ($cart->hasMoved()) {
                    continue;
                }

                list($newX, $newY) = $cart->getNextPosition();
                $cart->move($tracks[$newY][$newX], $newX, $newY);

                if (!is_null($carts[$newY][$newX])) {
                    // collision
                    return [$newX, $newY];
                } else {
                    $carts[$y][$x] = null;
                    $carts[$newY][$newX] = $cart;
                }

            }
        }
        return false;
    }


    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function testInput()
    {
        return [
            '/->-\        ',
            '|   |  /----\\',
            '| /-+--+-\  |',
            '| | |  | v  |',
            '\-+-/  \-+--/',
            '  \------/   ',
        ];
    }

    /**
     * @param bool $testTracks
     * @return array
     */
    protected function buildTracks($testTracks = false)
    {
        if ($testTracks) {
            $lines = $this->testInput();
        } else {
            $lines = $this->readinput();
        }

        $width = strlen($lines[0]);
        $height = count($lines);
        $tracks = [];
        $carts = [];

        $y = 0;
        foreach ($lines as $line) {
            $x = 0;
            foreach (str_split($line) as $char) {
                $carts[$y][$x] = null;

                if ($char == '>') {
                    $carts[$y][$x] = new Cart($x, $y, Cart::DIR_RIGHT);
                    $tracks[$y][$x] = '-';
                } elseif ($char == '<') {
                    $carts[$y][$x] = new Cart($x, $y, Cart::DIR_LEFT);
                    $tracks[$y][$x] = '-';
                } elseif ($char == '^') {
                    $carts[$y][$x] = new Cart($x, $y, Cart::DIR_UP);
                    $tracks[$y][$x] = '|';
                } elseif ($char == 'v') {
                    $carts[$y][$x] = new Cart($x, $y, Cart::DIR_DOWN);
                    $tracks[$y][$x] = '|';
                } else {
                    $tracks[$y][$x] = $char;
                }

                $x += 1;
            }
            $y += 1;
        }

        return [$tracks, $width, $height, $carts];
    }
}
