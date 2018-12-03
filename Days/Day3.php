<?php


namespace AoC2018\Days;


class Day3 extends AbstractDay
{
    protected $title = 'No Matter How You Slice It';

    public function run()
    {
        $this->logTitle();
        $this->logResult($this->part1());
        $this->logResult($this->part2());
    }

    protected function part1()
    {
        $tiles = $this->readinput();
        $combinations = $this->createCombinations($tiles);
        $maxCombos = count($combinations);
        $overlappingTiles = [];
        $i = 0;
        foreach ($combinations as $combo) {
            $tile1 = $combo[0];
            $tile2 = $combo[1];
            $i += 1;
            $p = (int)($i / $maxCombos) * 100.0;
            if ($i % ((int)($maxCombos/100)) == 0) {
                $this->logLine("$p%");
            }
            $newOverlaps = $this->overlapTiles($tile1, $tile2);
            if ($newOverlaps !== false) {
                $overlappingTiles = array_merge($overlappingTiles, $newOverlaps);
            }
        }
        return count($overlappingTiles);
    }

    /**
     * Create permutations of an array such that the same
     * items are not contained twice in the result
     *
     * @param $arr
     * @return array
     */
    protected function createCombinations($arr)
    {
        $result = [];
        foreach ($arr as $key1 => $item1) {
            foreach ($arr as $key2 => $item2) {
                if ($key1 == $key2) {
                    continue;
                }
                $result[] = [$item1, $item2];
            }
            unset($arr[$key1]);
        }
        return $result;
    }

    protected function overlapTiles($tile1, $tile2)
    {
        // calculate the bottom-right corners
        $t1endX = $tile1['x'] + $tile1['w'];
        $t1endY = $tile1['y'] + $tile1['h'];

        $t2endX = $tile2['x'] + $tile2['w'];
        $t2endY = $tile2['y'] + $tile2['h'];

        $X1 = max($tile1['x'], $tile2['x']);
        $Y1 = max($tile1['y'], $tile2['y']);
        $X2 = min($t1endX, $t2endX);
        $Y2 = min($t1endY, $t2endY);

        if ($X1 > $X2 || $Y1 > $Y2) {
            // no overlap
            return false;
        }

        $overlap = [];
        for($y = $Y1; $y < $Y2; $y++) {
            for($x = $X1; $x < $X2; $x++) {
                $overlap["$x,$y"] = true;
            }
        }
        return $overlap;
    }

    protected function part2()
    {

    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        $list = array_filter(explode("\n", file_get_contents($file)), 'strlen');
        $idList = [];
        foreach ($list as $item) {
            if (preg_match('~#(\d+)\s@\s(\d+),(\d+):\s(\d+)x(\d+)~', $item, $match)) {
                $idList[$match[1]] = [
                    'id' => (int)$match[1],
                    'x' => (int)$match[2],
                    'y' => (int)$match[3],
                    'w' => (int)$match[4],
                    'h' => (int)$match[5],
                ];
            }
        }
        return $idList;
    }
}
