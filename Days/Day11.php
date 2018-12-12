<?php


namespace AoC2018\Days;


class Day11 extends AbstractDay
{
    protected $title = 'Chronal Charge';

    protected function part1()
    {
        $gridSerialNumber = $this->readinput();
        $gridMax = 300;
        $grid = $this->createGrid($gridMax, $gridSerialNumber);
        list($subgridPos) = $this->findLargestSubgrid($grid, $gridMax, 3, 3);
        return implode(',', $subgridPos);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $gridSerialNumber
     * @return int
     */
    protected function calculatePowerLevel($x, $y, $gridSerialNumber)
    {
        $rackID = $x + 10;
        $power = $y * $rackID;
        $power += $gridSerialNumber;
        $power *= $rackID;
        if ($power >= 100) {
            $power = (int)substr($power, -3, 1);
        } else {
            $power = 0;
        }
        $power -= 5;
        return $power;
    }

    /**
     * Create an array filles with calculated power
     *
     * @param int $gridMax
     * @param int $gridSerialNumber
     * @return array
     */
    protected function createGrid($gridMax, $gridSerialNumber)
    {
        $grid = [];
        for ($y = 1; $y < $gridMax + 1; $y++) {
            for ($x = 1; $x < $gridMax + 1; $x++) {
                $grid[$y][$x] = $this->calculatePowerLevel($x, $y, $gridSerialNumber);
            }
        }
        return $grid;
    }


    /**
     * Returns top left coordinate of the subgrid with largest total value
     *
     * @param array $grid
     * @param int $gridMax
     * @param int $subWidth
     * @param int $subHeight
     * @return array[[x, y], MaxValue]
     */
    protected function findLargestSubgrid($grid, $gridMax, $subWidth, $subHeight)
    {
        $subGridMaxX = $gridMax - $subWidth + 1;
        $subGridMaxY = $gridMax - $subHeight + 1;
        $subGrid = [null, null];
        $subGridMaxValue = PHP_INT_MIN;

        for ($y = 1; $y < $subGridMaxY + 1; $y++) {
            for ($x = 1; $x < $subGridMaxX + 1; $x++) {

                $subGridValue = 0;
                for ($sy = 0; $sy < $subHeight; $sy++) {
                    for ($sx = 0; $sx < $subWidth; $sx++) {
                        $subGridValue += $grid[$y + $sy][$x + $sx];
                    }
                }
                if ($subGridValue > $subGridMaxValue) {
                    $subGridMaxValue = $subGridValue;
                    $subGrid = [$x, $y];
                }

            }
        }

        return [$subGrid, $subGridMaxValue];
    }

    protected function part2()
    {
        $gridSerialNumber = $this->readinput();
        $gridMax = 300;
        $grid = $this->createGrid($gridMax, $gridSerialNumber);


        $this->logText("Building Lookup Table... ");
        $summedAreaTable = $this->createSummedAreaTable($grid, $gridMax);
        $this->logText("[G]OK");

        $this->logText("Searching largest Subgrid...");
        $subgridMax = $this->findLargestSubgridFromAreaTable($summedAreaTable, $gridMax);

        return implode(',', $subgridMax);
    }

    /**
     * Could be faster if previous sums where reused...
     * takes about 1m, 25s
     *
     * @param array $grid
     * @param int $gridMax
     * @return array
     */
    protected function createSummedAreaTable($grid, $gridMax)
    {
        $summedAreaTable = [];
        $i = 0;
        for ($y = 1; $y < $gridMax + 1; $y++) {
            for ($x = 1; $x < $gridMax + 1; $x++) {
                $summedAreaTable[$y][$x] = 0;
                for ($ys = 1; $ys < $y + 1; $ys++) {
                    for ($xs = 1; $xs < $x + 1; $xs++) {
                        $summedAreaTable[$y][$x] += $grid[$ys][$xs];
                    }
                }
                $i += 1;
                $this->logProgress('percent-bar-small', $i, $gridMax * $gridMax);
            }
        }
        $this->logProgress('reset');
        return $summedAreaTable;
    }

    /**
     * Find the subgrid size with the highest absolute value
     * https://en.wikipedia.org/wiki/Summed-area_table
     * simple approach took about 40min to finish
     *
     * @param array $summedAreaTable
     * @param int $gridMax
     * @param int $subGridMin
     * @return array
     */
    protected function findLargestSubgridFromAreaTable($summedAreaTable, $gridMax, $subGridMin = 1)
    {
        $subGridMaxValue = PHP_INT_MIN;
        $subGrid = [null, null, null];
        for ($i = $subGridMin; $i < $gridMax + 1; $i++) {

            for ($y = $i; $y < $gridMax + 1; $y++) {
                for ($x = $i; $x < $gridMax + 1; $x++) {

                    $sum = $summedAreaTable[$y][$x];
                    $px = $x - $i;
                    $py = $y - $i;

                    // Upper Right
                    if ($py >= 1) {
                        $sum -= $summedAreaTable[$py][$x];
                    }
                    // Bottom Left
                    if ($px >= 1) {
                        $sum -= $summedAreaTable[$y][$px];
                    }
                    // Upper Left
                    if ($py >= 1 && $px >= 1) {
                        $sum += $summedAreaTable[$py][$px];
                    }

                    if ($sum > $subGridMaxValue) {
                        $subGridMaxValue = $sum;
                        $subGrid = [$x - $i + 1, $y - $i + 1, $i];
                    }
                    $this->logProgress('percent-bar-small', $i, $gridMax);
                }
            }
        }
        $this->logProgress('reset');
        return $subGrid;
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return (int)file_get_contents($file);
    }
}
