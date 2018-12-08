<?php


namespace AoC2018\Days\Day8;


class Node
{

    /**
     * The quantity of child nodes.
     * The quantity of metadata entries.
     *
     * @var array
     */
    protected $header = [];

    /**
     * @var array
     */
    protected $metaData = [];

    /**
     * @var Node[]
     */
    protected $children = [];

    public function __construct($header)
    {
        $this->header = $header;
    }

    public function addMetaData($m)
    {
        $this->metaData[] = $m;
    }

    public function getMetaDataSum()
    {
        return array_reduce(
            $this->metaData,
            function ($carry, $value) {
                return $carry += (int)$value;
            }
        );
    }

    public function addChildNode($node)
    {
        $this->children[] = $node;
    }

    public function getValue()
    {
        if ($this->header[0] == 0) {
            return $this->getMetaDataSum();
        }
        $sum = 0;
        foreach ($this->metaData as $nodeId) {
            $key = (int)$nodeId - 1;
            if (isset($this->children[$key])) {
                $sum += $this->children[$key]->getValue();
            }
        }
        return $sum;
    }
}
