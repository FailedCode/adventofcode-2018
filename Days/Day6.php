<?php


namespace AoC2018\Days;


class Day6 extends AbstractDay
{
    protected $title = 'Chronal Coordinates';

    protected function part1()
    {
        $coordinates = $this->getCoordinates();
        $boundingCoords = $this->getBoundingCoords($coordinates);
        $Ymax = $boundingCoords[1][1];
        $Xmax = $boundingCoords[1][0];

        $map = [];
        $mapMinDist = [];
        foreach ($coordinates as $name => $coordinate) {
            for ($y = 0; $y < $Ymax; $y++) {
                for ($x = 0; $x < $Xmax; $x++) {

                    // initalize empty fields
                    if (!isset($map[$y][$x])) {
                        $map[$y][$x] = '';
                        $mapMinDist[$y][$x] = PHP_INT_MAX;
                    }

                    $dist = $this->manhattenDistance($coordinate, [$x, $y]);
                    if ($dist < $mapMinDist[$y][$x]) {
                        // such a small distance is new: record the coordinates name
                        $mapMinDist[$y][$x] = $dist;
                        $map[$y][$x] = $name;
                    } elseif ($dist == $mapMinDist[$y][$x]) {
                        // anthoder coordinate already had the same distance
                        $map[$y][$x] = '.';
                    }
                }
            }
        }

        // map is filled now, find every border char as those would go on ad infinitum
        $ignoreChars = [];
        for ($y = 0; $y < $Ymax; $y++) {
            $ignoreChars[] = $map[$y][0];
            $ignoreChars[] = $map[$y][$Xmax - 1];
        }
        for ($x = 0; $x < $Xmax; $x++) {
            $ignoreChars[] = $map[0][$x];
            $ignoreChars[] = $map[$Ymax - 1][$x];
        }
        $ignoreChars = array_unique($ignoreChars);

        // now count fields
        $count = [];
        for ($y = 0; $y < $Ymax; $y++) {
            for ($x = 0; $x < $Xmax; $x++) {
                // ignore multiples
                if ($map[$y][$x] == '.') {
                    continue;
                }
                if (array_search($map[$y][$x], $ignoreChars) !== false) {
                    continue;
                }
                if (!isset($count[$map[$y][$x]])) {
                    $count[$map[$y][$x]] = 0;
                }
                $count[$map[$y][$x]] += 1;
            }
        }

        return max($count);
    }

    protected function getBoundingCoords($coordinates)
    {
        $Xmin = PHP_INT_MAX;
        $Xmax = -1;
        $Ymin = PHP_INT_MAX;
        $Ymax = -1;
        foreach ($coordinates as $coord) {
            if ($coord[0] < $Xmin) $Xmin = $coord[0];
            if ($coord[1] < $Ymin) $Ymin = $coord[1];
            if ($coord[0] > $Xmax) $Xmax = $coord[0];
            if ($coord[1] > $Ymax) $Ymax = $coord[1];
        }
        return [[$Xmin, $Ymin], [$Xmax, $Ymax]];
    }

    /**
     * Input: Absolute coordinates
     *
     * @param array $point1 [x, y]
     * @param array $point2 [x, y]
     * @return int
     */
    protected function manhattenDistance($point1, $point2)
    {
        $xdiff = abs($point1[0] - $point2[0]);
        $ydiff = abs($point1[1] - $point2[1]);
        return $xdiff + $ydiff;
    }

    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function getCoordinates()
    {
        $coordinates = [];
        $i = 65;
        foreach ($this->readinput() as $line) {
            list($x, $y) = explode(',', $line);
            $coordinates[chr($i)] = [(int)$x, (int)$y];
            $i += 1;
        }
        return $coordinates;
    }
}
