<?php


namespace AoC2018\Days\Day15;

class Unit
{
    protected $type = '';
    protected $y = 0;
    protected $x = 0;
    protected $hitPoints = 200;
    protected $attackPower = 3;

    protected static $unitList = [];
    protected static $unitTypeList = [];

    protected static $cli;

    public function __construct($type, $x, $y)
    {
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
        self::$unitList[] = $this;
        if (!isset(self::$unitTypeList[$type])) {
            self::$unitTypeList[$type] = [];
        }
        self::$unitTypeList[$type][] = $this;
    }

    public static function setCli($cli)
    {
        self::$cli = $cli;
    }

    public static function remove($unit)
    {
        if (($key = array_search($unit, self::$unitTypeList[$unit->type])) !== false) {
            unset(self::$unitTypeList[$unit->type][$key]);
        }
        if (($key = array_search($unit, self::$unitList)) !== false) {
            unset(self::$unitList[$key]);
        }
    }

    public static function getList($type = null)
    {
        if (is_null($type)) {
            return self::$unitList;
        } elseif (isset(self::$unitTypeList[$type])) {
            return self::$unitTypeList[$type];
        }
        return false;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHP()
    {
        return $this->hitPoints;
    }

    public function getPosition()
    {
        return [$this->x, $this->y];
    }

    public function setPosition($position)
    {
        $this->x = $position[0];
        $this->y = $position[1];
    }

    /**
     * @param $unitMap
     * @return bool|mixed
     */
    public function getAdjectedEnemy($unitMap)
    {
        $enemies = [];
        // order of the coordinates is important
        // use sorting which keeps same values in place
        foreach ([[0, -1], [-1, 0], [1, 0], [0, 1]] as $position) {
            $x = $position[0] + $this->x;
            $y = $position[1] + $this->y;
            if (isset($unitMap[$y][$x]) && $this->type != $unitMap[$y][$x]->type) {
                $enemies[] = $unitMap[$y][$x];
            }
        }
        if (count($enemies)) {
            usort($enemies, function ($a, $b) {
                return $a->hitPoints - $b->hitPoints;
            });
            return $enemies[0];
        }
        return false;
    }

    /**
     * @param Unit $enemy
     * @return bool
     */
    public function fight($enemy)
    {
        $enemy->hitPoints -= $this->attackPower;
        if ($enemy->hitPoints <= 0) {
            self::remove($enemy);
            return true;
        }
        return false;
    }

    public function getNewPosition($tileMap)
    {
        $newPositions = [];
        $units = self::getList();

        /** @var Unit $unit */
        foreach ($units as $unit) {
            if ($unit->type == $this->type) {
                continue;
            }
            $path = $this->getPathAStar($this->getPosition(), $unit->getPosition(), $tileMap);
            if ($path !== false) {
                $newPositions[] = $path[0];
            }
        }

        //$newPositions = array_unique($newPositions);
        $enemiesCount = count($newPositions);
//        if ($enemiesCount > 1) {
//            usort($enemies, function ($a, $b){
//                // difference of path length
//                return $a[1] - $b[1];
//            });
//        }

        if ($enemiesCount > 0) {
            return $newPositions[0];
        }
        return false;
    }


    /**
     * @param $start
     * @param $target
     * @param $tileMap
     * @return array|bool
     */
    protected static function getPathAStar($start, $target, $tileMap)
    {
        $startKey = self::posToKey($start);

        $closedSet = [];
        $openSet = [];
        $openSet[$startKey] = $start;
        $cameFrom = [];

        // weight for start to there
        $gScore = [];
        $gScore[$startKey] = 0;

        // weight for target to there
        $fScore = [];
        $fScore[$startKey] = self::manhattanDistance($start, $target);

        while (count($openSet)) {
            $currentKey = self::getMinimalFScore($openSet, $fScore);
            $current = self::keyToPos($currentKey);
            if ($current == $target) {
                if (empty($cameFrom)) {
                    return false;
                }
                $totelPath = [];
                $totelPath[] = $current;
                while (isset($cameFrom[$currentKey])) {
                    $current = $cameFrom[$currentKey];
                    $currentKey = self::posToKey($current);
                    if ($startKey == $currentKey) {
                        break;
                    }
                    $totelPath[] = $current;
                }
                return array_reverse($totelPath);
            }

            unset($openSet[$currentKey]);
            $closedSet[$currentKey] = $current;

            foreach ([[0, -1], [-1, 0], [1, 0], [0, 1]] as $nPos) {
                $neighbor = [$nPos[0] + $current[0], $nPos[1] + $current[1]];
                $neighborKey = self::posToKey($neighbor);

                // already evaluated
                if (isset($closedSet[$neighborKey])) {
                    continue;
                }

                // test here if the field actually can be used
                if ($neighbor != $target && $tileMap[$neighbor[1]][$neighbor[0]] == 1) {
                    $closedSet[$neighborKey] = $neighbor;
                    continue;
                }

                // distance from current to neighbor is always 1
                $tentative_gScore = $gScore[$currentKey] + 1;


                // default = infinity
                $gScoreThing = isset($gScore[$neighborKey]) ? $gScore[$neighborKey] : PHP_INT_MAX;
                if (!isset($openSet[$neighborKey])) {
                    // add neighbor to searchable fields
                    $openSet[$neighborKey] = $neighbor;
                } elseif ($tentative_gScore >= $gScoreThing) {
                    // new score is same or bigger
                    continue;
                }

                $cameFrom[$neighborKey] = $current;
                $gScore[$neighborKey] = $tentative_gScore;
                $fScore[$neighborKey] = $gScore[$neighborKey] + self::manhattanDistance($neighbor, $target);
            }

        }
        // no path found!
        return false;
    }

    protected static function posToKey($position)
    {
        return "{$position[0]},{$position[1]}";
    }

    protected static function keyToPos($key)
    {
        return explode(',', $key);
    }


    /**
     * Return the node in openSet having the lowest fScore[] value
     *
     * @param array $openSet
     * @param array $fScore
     * @return string
     */
    protected static function getMinimalFScore($openSet, $fScore)
    {
        while (count($fScore)) {
            $key = array_keys($fScore, min($fScore))[0];
            if (!isset($openSet[$key])) {
                unset($fScore[$key]);
            } else {
                return $key;
            }
        }
    }

    /**
     * @param $p1
     * @param $p2
     * @return float|int
     */
    protected static function manhattanDistance($p1, $p2)
    {
        return abs($p1[1] - $p2[1]) + abs($p1[0] - $p2[0]);
    }
}
