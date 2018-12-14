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
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return (int)file_get_contents($file);
    }
}
