<?php


namespace AoC2018\Days\Day9;

class Marble
{
    /**
     * @var int
     */
    protected $value;

    /**
     * @var Marble
     */
    protected $prev;

    /**
     * @var Marble
     */
    protected $next;

    /**
     * Marble constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->prev = $this;
        $this->next = $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getNext($count = 1)
    {
        $marble = $this;
        for ($i = 0; $i < $count; $i++) {
            $marble = $marble->next;
        }
        return $marble;
    }

    public function getPrevious($count = 1)
    {
        $marble = $this;
        for ($i = 0; $i < $count; $i++) {
            $marble = $marble->prev;
        }
        return $marble;
    }

    public function insertAfter($value)
    {
        $newMarble = new Marble($value);
        $next = $this->next;
        $this->next = $newMarble;

        $newMarble->prev = $this;
        $newMarble->next = $next;
        $newMarble->next->prev = $newMarble;
        $newMarble->prev->next = $newMarble;

        return $newMarble;
    }

    public function remove()
    {
        $remove = $this;
        $next = $remove->next;
        $prev = $remove->prev;
        $remove->next->prev = $prev;
        $remove->prev->next = $next;
        $remove->next = null;
        $remove->prev = null;
        return $next;
    }

    public function log()
    {
        return $this->prev->prev->getValue() .
            " <- " . $this->prev->getValue() .
            " <- " . $this->getValue() . " -> " .
            $this->next->getValue() . " -> " .
            $this->next->next->getValue();
    }

    public function toArray()
    {
        $marbles = [];
        $start = $this;
        $marble = $this;
        while (true) {
            $marbles[] = $marble->value;
            $marble = $marble->next;
            if ($marble === $start) {
                return $marbles;
            }
        }
    }
}
