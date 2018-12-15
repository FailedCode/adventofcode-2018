<?php


namespace AoC2018\Days\Day14;

use AoC2018\Utility\Cli;

class Recipe
{
    /**
     * @var int
     */
    protected $value;

    /**
     * @var Recipe;
     */
    protected $next;

    /**
     * @var Recipe
     */
    protected static $first;

    /**
     * @var Recipe
     */
    protected static $last;

    /**
     * @var int
     */
    protected static $count = 0;

    /**
     * @var Cli
     */
    protected static $cli;

    /**
     * Recipe constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;

        if (is_null(self::$first)) {
            self::$first = $this;
        }
        $this->next = self::$first;

        if (!is_null(self::$last)) {
            self::$last->next = $this;
        }
        self::$last = $this;
        self::$count += 1;
    }

    public static function count()
    {
        return self::$count;
    }

    public static function setCli($cli)
    {
        self::$cli = $cli;
    }

    public static function log()
    {
        $el = self::$first;
        do {
            self::$cli->log("{$el->value} ");
            $el = $el->next;
        } while ($el !== self::$first);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getNext($n)
    {
        $link = $this;
        for ($i = 0; $i < $n; $i++) {
            $link = $link->next;
        }
        return $link;
    }
}
