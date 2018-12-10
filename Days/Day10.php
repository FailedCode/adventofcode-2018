<?php


namespace AoC2018\Days;

use AoC2018\Days\Day10\Char;

class Day10 extends AbstractDay
{
    protected $title = 'The Stars Align';

    protected $cachedTime = 0;

    protected function part1()
    {
        $points = $this->getPoints();

        $areaOld = PHP_INT_MAX;
        $area = $this->pointArea($points);
        $i = 0;
        while ($areaOld > $area) {
            $this->movePoints($points);
            $areaOld = $area;
            $area = $this->pointArea($points);
            $i += 1;
            $this->logProgress('progress-value', (int)$area);
        }
        $this->logProgress('reset');
        $this->logText("Minimal average area: [Y]{$area}[] (after [B]{$i}[] Iterations)");
        $this->cachedTime = $i - 1;
        $this->movePoints($points, true);
        $this->normalizePoints($points);
        $text = $this->recognizePoints($points);
        if (!$text) {
            $this->logText("[R]Some characters unknown, please add them to [Y]Day10/Char.php");
            $this->drawPoints($points);
        }
        return $text;
    }

    protected function movePoints(&$points, $moveBack = false)
    {
        foreach ($points as &$point) {
            if ($moveBack) {
                $point['x'] -= $point['dx'];
                $point['y'] -= $point['dy'];
            } else {
                $point['x'] += $point['dx'];
                $point['y'] += $point['dy'];
            }
        }
    }

    protected function pointArea($points)
    {
        $box = $this->getBoundingBox($points);
        $w = $box['max']['x'] - $box['min']['x'];
        $h = $box['max']['y'] - $box['min']['y'];
        return $w * $h;
    }

    protected function getBoundingBox($points)
    {
        $Xmin = PHP_INT_MAX;
        $Xmax = -1;
        $Ymin = PHP_INT_MAX;
        $Ymax = -1;
        foreach ($points as $point) {
            $Xmin = min($Xmin, $point['x']);
            $Xmax = max($Xmax, $point['x']);
            $Ymin = min($Ymin, $point['y']);
            $Ymax = max($Ymax, $point['y']);
        }
        return [
            'min' => ['x' => $Xmin, 'y' => $Ymin],
            'max' => ['x' => $Xmax, 'y' => $Ymax]
        ];
    }

    protected function normalizePoints(&$points)
    {
        $boundingBox = $this->getBoundingBox($points);
        foreach ($points as &$point) {
            $point['x'] -= $boundingBox['min']['x'];
            $point['y'] -= $boundingBox['min']['y'];
        }
        usort($points, function ($a, $b) {
            $dy = $a['y'] - $b['y'];
            if ($dy == 0) {
                return $a['x'] - $b['x'];
            }
            return $dy;
        });
    }

    protected function drawPoints($points)
    {
        $boundingBox = $this->getBoundingBox($points);
        $Ymax = $boundingBox['max']['y'] + 1;
        $Xmax = $boundingBox['max']['x'] + 1;
        $map = [];
        for ($y = 0; $y < $Ymax; $y++) {
            for ($x = 0; $x < $Xmax; $x++) {
                foreach ($points as $point) {
                    if (!isset($map[$y][$x])) {
                        $map[$y][$x] = ' ';
                    }

                    if ($map[$y][$x] !== ' ') {
                        continue;
                    }

                    if ($point['x'] == $x && $point['y'] == $y) {
                        $map[$y][$x] = '#';
                    }
                }
            }
        }

        $this->logLine('');
        for ($y = 0; $y < $Ymax; $y++) {
            for ($x = 0; $x < $Xmax; $x++) {
                $this->log($map[$y][$x]);
            }
            $this->logLine('');
        }
        $this->logLine('');
    }

    protected function recognizePoints($points)
    {
        $text = '';
        $chars = 8;
        $width = 8;
        $height = 10;

        for ($c = 0; $c < $chars; $c++) {
            $data = '';
            $display = '';

            $Xstart = $c * $width;
            $Ystart = 0;

            $Xmax = $Xstart + $width;
            $Ymax = $Ystart + $height;

            $map = [];
            for ($y = $Ystart; $y < $Ymax; $y++) {
                for ($x = $Xstart; $x < $Xmax; $x++) {
                    foreach ($points as $point) {
                        if (!isset($map[$y][$x])) {
                            $map[$y][$x] = ' ';
                        }
                        if ($map[$y][$x] !== ' ') {
                            continue;
                        }
                        if ($point['x'] == $x && $point['y'] == $y) {
                            $map[$y][$x] = '#';
                        }
                    }
                }
                $data .= implode('', $map[$y]);
                $display .= implode('', $map[$y]) . "\n";
            }
            $char = Char::get($data);
            if (!$char) {
                $this->logText("Can't recognize: \n");
                $this->log($display);
                return false;
            }
            $text .= $char;
        }
        return $text;
    }

    protected function part2()
    {
        if ($this->cachedTime > 0) {
            // In case the test runs only part 2, we recalculate
            // otherwise no need to wait..
            return $this->cachedTime;
        }
        $points = $this->getPoints();
        $areaOld = PHP_INT_MAX;
        $area = $this->pointArea($points);
        $i = 0;
        while ($areaOld > $area) {
            $this->movePoints($points);
            $areaOld = $area;
            $area = $this->pointArea($points);
            $i += 1;
            $this->logProgress('progress-value', (int)$area);
        }
        $this->logProgress('reset');
        return $i - 1;
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function getPoints()
    {
        $lines = $this->readinput();
        $points = [];
        foreach ($lines as $line) {
            preg_match_all('~(-?\d+)~', $line, $match);
            $points[] = [
                'x' => (int)$match[1][0],
                'y' => (int)$match[1][1],
                'dx' => (int)$match[1][2],
                'dy' => (int)$match[1][3],
            ];
        }
        return $points;
    }
}
