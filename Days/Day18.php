<?php


namespace AoC2018\Days;


class Day18 extends AbstractDay
{
    protected $title = 'Settlers of The North Pole';

    protected function part1()
    {
        $draw = false;
        list($map, $width, $height) = $this->loadMap();
        if ($draw) {
            $this->renderMap($map, $width, $height, false);
            $this->waitkey();
        }

        $generations = 10;
        for ($i = 0; $i < $generations; $i++) {
            $map = $this->updateMap($map, $width, $height);
            if ($draw) {
                $this->renderMap($map, $width, $height);
                $this->waitkey();
            }
        }

        $types = $this->countTypes($map, $width, $height);

        // 132126
        return $types['#'] * $types['|'];
    }

    protected function part2()
    {
    }

    protected function updateMap($map, $width, $height)
    {
        $newMap = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {

                $typeCount = [
                    '.' => 0,
                    '#' => 0,
                    '|' => 0,
                ];
                for ($yd = -1; $yd <= 1; $yd++) {
                    for ($xd = -1; $xd <= 1; $xd++) {
                        if ($yd == 0 && $xd == 0) {
                            continue;
                        }
                        $ys = $y + $yd;
                        $xs = $x + $xd;
                        if (!isset($map[$ys][$xs])) {
                            continue;
                        }
                        $typeCount[$map[$ys][$xs]] += 1;
                    }
                }

                switch ($map[$y][$x]) {
                    case '.':
                        $newMap[$y][$x] = ($typeCount['|'] > 2) ? '|' : '.';
                        break;
                    case '#':
                        $newMap[$y][$x] = (($typeCount['#'] > 0) && ($typeCount['|'] > 0)) ? '#' : '.';
                        break;
                    case '|':
                        $newMap[$y][$x] = ($typeCount['#'] > 2) ? '#' : '|';
                        break;
                }

            }
        }
        return $newMap;
    }

    protected function renderMap($map, $width, $height, $erease = true)
    {
        if ($erease) {
            echo chr(27) . "[" . $height . "A";
        }

        for ($y = 0; $y < $height; $y++) {
            $line = '';
            for ($x = 0; $x < $width; $x++) {
                $color = '';
                switch ($map[$y][$x]) {
                    case '.':
                        $color = 'brown';
                        break;
                    case '|':
                        $color = 'green';
                        break;
                    case '#':
                        $color = 'yellow';
                        break;
                }
                $line .= $this->colorString($map[$y][$x], $color);
            }
            $this->logText($line);
        }
    }

    protected function countTypes($map, $width, $height)
    {
        $types = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $type = $map[$y][$x];
                if (!isset($types[$type])) {
                    $types[$type] = 0;
                }
                $types[$type] += 1;
            }
        }
        return $types;
    }

    protected function readinput($name = '')
    {
        $file = $this->getInputFile($name);
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function loadMap($name = '')
    {
        $lines = $this->readinput($name);
        $map = [];
        $height = count($lines);
        $width = strlen($lines[0]);
        $y = 0;
        foreach ($lines as $line) {
            $x = 0;
            foreach (str_split($line) as $char) {
                $map[$y][$x] = $char;
                $x += 1;
            }
            $y += 1;
        }
        return [$map, $width, $height];
    }
}
