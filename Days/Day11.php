<?php


namespace AoC2018\Days;


class Day11 extends AbstractDay
{
    protected $title = 'Chronal Charge';

    protected function part1()
    {
        $gridSerialNumber = $this->readinput();
        $grid = $this->createGrid(300, $gridSerialNumber);
        list($subgridPos) = $this->findLargestSubgrid($grid, 300, 3, 3);
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
        $grid = $this->createGrid(300, $gridSerialNumber);
        $subgridMax = $this->findLargestSubgridSize($grid, 300);
        return implode(',', $subgridMax);
    }

    /**
     * Find the subgrid size with the highest absolute value
     * takes about 40min to finish
     *
     * @param array $grid
     * @param int $gridMax
     * @param int $subGridMin
     * @return array
     */
    protected function findLargestSubgridSize($grid, $gridMax, $subGridMin = 3)
    {
        $subGridMaxValue = PHP_INT_MIN;
        $subGrid = [null, null, null];
        for ($i = $subGridMin; $i < $gridMax + 1; $i++) {
            list($newSubGrid, $newSubGridValue) = $this->findLargestSubgrid($grid, $gridMax, $i, $i);
            if ($newSubGridValue > $subGridMaxValue) {
                $subGridMaxValue = $newSubGridValue;
                $subGrid = $newSubGrid;
                $subGrid[] = $i;
            }
            $this->logProgress('percent-bar-small', $i, $gridMax);
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
