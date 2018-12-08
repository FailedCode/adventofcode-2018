<?php


namespace AoC2018\Days;

use AoC2018\Days\Day8\Node;


class Day8 extends AbstractDay
{
    protected $title = 'Memory Maneuver';

    protected function part1()
    {
        $numbers = $this->readinput();

        $allNodes = [];
        $this->addNodeRecursive($allNodes, $numbers);

        return array_reduce(
            $allNodes,
            function ($carry, $node) {
                $carry += $node->getMetaDataSum();
                return $carry;
            }
        );
    }

    protected function addNodeRecursive(&$allNodes, &$numbers)
    {
        $childNodes = array_shift($numbers);
        $metadataEntries = array_shift($numbers);
        $node = new Node($childNodes, $metadataEntries);
        $allNodes[] = $node;
        for ($i = 0; $i < $childNodes; $i++) {
            $node->addChildNode($this->addNodeRecursive($allNodes, $numbers));
        }
        for ($i = 0; $i < $metadataEntries; $i++) {
            $node->addMetaData(array_shift($numbers));
        }
        return $node;
    }

    protected function part2()
    {
        $numbers = $this->readinput();

        $allNodes = [];
        $root = $this->addNodeRecursive($allNodes, $numbers);

        return $root->getValue();
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_map('intval', explode(" ", file_get_contents($file)));
    }
}
