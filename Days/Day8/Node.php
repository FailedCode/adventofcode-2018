<?php


namespace AoC2018\Days\Day8;


class Node
{

    /**
     * The quantity of child nodes.
     *
     * @var int
     */
    protected $childNodeQuantity = 0;

    /**
     * The quantity of metadata entries.
     *
     * @var int
     */
    protected $metadataQuantity = 0;

    /**
     * @var array
     */
    protected $metaData = [];

    /**
     * @var Node[]
     */
    protected $children = [];

    public function __construct($childNodeQuantity, $metadataQuantity)
    {
        $this->childNodeQuantity = $childNodeQuantity;
        $this->metadataQuantity = $metadataQuantity;
    }

    /**
     * @param int $m
     */
    public function addMetaData($m)
    {
        $this->metaData[] = $m;
    }

    /**
     * @return int
     */
    public function getMetaDataSum()
    {
        return array_reduce(
            $this->metaData,
            function ($carry, $value) {
                $carry += (int)$value;
                return $carry;
            }
        );
    }

    /**
     * @param Node $node
     */
    public function addChildNode($node)
    {
        $this->children[] = $node;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        if ($this->childNodeQuantity == 0) {
            return $this->getMetaDataSum();
        }
        $value = 0;
        foreach ($this->metaData as $nodeId) {
            $key = $nodeId - 1;
            if (isset($this->children[$key])) {
                $value += $this->children[$key]->getValue();
            }
        }
        return $value;
    }
}
