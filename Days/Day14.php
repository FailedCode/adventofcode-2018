<?php


namespace AoC2018\Days;

use AoC2018\Days\Day14\Recipe;

class Day14 extends AbstractDay
{
    protected $title = 'Chocolate Charts';

    protected function part1()
    {
        Recipe::setCli($this);
        $maxCount = $this->readinput();
        $cooks = [];
        $cooks[] = new Recipe(3);
        $cooks[] = new Recipe(7);

        $recordDigits = 10;
        $lastDigits = [];
        while (true) {
            $newRecipeSum = array_reduce($cooks, function ($carry, $element) {
                $carry += $element->getValue();
                return $carry;
            });
            foreach (str_split($newRecipeSum) as $digit) {
                new Recipe($digit);
                if (count($lastDigits) == $recordDigits) {
                    array_shift($lastDigits);
                }
                $lastDigits[] = $digit;
                if (Recipe::count() == ($maxCount + $recordDigits)) {
                    break 2;
                }
            }
            foreach ($cooks as &$cook) {
                $cook = $cook->getNext($cook->getValue() + 1);
            }
        }

        return implode('', $lastDigits);
    }

    protected function part2()
    {
        return $this->arrayVersion();
    }

    /**
     * Time: 1h, 22m
     *
     * @return int
     */
    protected function linkVersion()
    {
        Recipe::setCli($this);
        $searchPattern = str_split($this->readinput());
        $cooks = [];
        $cooks[] = new Recipe(3);
        $cooks[] = new Recipe(7);

        $recordDigits = count($searchPattern);
        $lastDigits = [];
        while (true) {
            $newRecipeSum = array_reduce($cooks, function ($carry, $element) {
                $carry += $element->getValue();
                return $carry;
            });
            foreach (str_split($newRecipeSum) as $digit) {
                new Recipe($digit);
                if (count($lastDigits) == $recordDigits) {
                    array_shift($lastDigits);
                }
                $lastDigits[] = $digit;
                if ($searchPattern == $lastDigits) {
                    break 2;
                }
            }
            foreach ($cooks as &$cook) {
                $cook = $cook->getNext($cook->getValue() + 1);
            }
            $this->logProgress('absolute', Recipe::count());
        }
        $this->logProgress('reset');

        return Recipe::count() - $recordDigits;
    }

    /**
     * Time: 12s
     *
     * @return int
     */
    protected function arrayVersion()
    {
        $searchPattern = str_split($this->readinput());
        $recipies = [3, 7];
        $recipiesCount = 2;
        $cooks = [0, 1];

        $recordDigits = count($searchPattern);
        $lastDigits = [];
        while (true) {
            $newRecipeSum = $recipies[$cooks[0]]  + $recipies[$cooks[1]];
            foreach (str_split($newRecipeSum) as $digit) {
                $recipies[] = $digit;
                $recipiesCount += 1;
                if (count($lastDigits) == $recordDigits) {
                    array_shift($lastDigits);
                }
                $lastDigits[] = $digit;
                if ($searchPattern == $lastDigits) {
                    break 2;
                }
            }
            foreach ($cooks as &$cook) {
                $cook = ($cook + $recipies[$cook] + 1) % $recipiesCount;
            }
            $this->logProgress('absolute', $recipiesCount);
        }
        $this->logProgress('reset');

        return $recipiesCount - $recordDigits;
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return (int)file_get_contents($file);
    }
}
