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
        $overlappingTiles = [];
        foreach ($combinations as $combo) {
            $tile1 = $combo[0];
            $tile2 = $combo[1];
            $newOverlaps = $this->overlapTiles($tile1, $tile2);
            // very important: empty arrays merge takes 900 s
            // only doing it with filled arrays takes 3 s
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

    /**
     * Returns either false or the coordinates of the collision rect
     *
     * @param $tile1
     * @param $tile2
     * @return array|bool
     */
    protected function rectsDoOverlap($tile1, $tile2)
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
            return false;
        }

        return compact('X1', 'Y1', 'X2', 'Y2');
    }

    /**
     * Returns false where no collisions occurs or
     * all coordinates as array keys
     *
     * @param $tile1
     * @param $tile2
     * @return array|bool
     */
    protected function overlapTiles($tile1, $tile2)
    {
        $overlapRect = $this->rectsDoOverlap($tile1, $tile2);
        if (!$overlapRect) {
            return false;
        }

        $overlap = [];
        for($y = $overlapRect['Y1']; $y < $overlapRect['Y2']; $y++) {
            for($x = $overlapRect['X1']; $x < $overlapRect['X2']; $x++) {
                $overlap["$x,$y"] = true;
            }
        }
        return $overlap;
    }

    protected function part2()
    {
        $tiles = $this->readinput();
        $combinations = $this->createCombinations($tiles);
        foreach ($combinations as $combo) {
            $tile1 = $combo[0];
            $tile2 = $combo[1];
            $overlapRect = $this->rectsDoOverlap($tile1, $tile2);
            if ($overlapRect !== false) {
                unset($tiles[$tile1['id']]);
                unset($tiles[$tile2['id']]);
            }
        }

        return array_keys($tiles)[0];
    }

    /**
     * @return array
     */
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
