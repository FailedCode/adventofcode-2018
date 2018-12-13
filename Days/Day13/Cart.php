<?php


namespace AoC2018\Days\Day13;


use function Sodium\crypto_aead_aes256gcm_decrypt;

class Cart
{
    protected $x;
    protected $y;
    protected $name;
    protected $hasMoved = false;

    /**
     * Absolute orientation
     *  0 - up
     *  1 - right
     *  2 - down
     *  3 - left
     * @var int
     */
    protected $direction;

    const DIR_UP = 0;
    const DIR_RIGHT = 1;
    const DIR_DOWN = 2;
    const DIR_LEFT = 3;
    const DIR_MOD = 4;

    /**
     * relative orientation for the next crossing
     *  -1 - left
     *  0 - straight
     *  1 - right
     * @var int
     */
    protected $crossDirection;

    const TURN_LEFT = -1;
    const TURN_STRAIGHT = 0;
    const TURN_RIGHT = 1;
    const TURN_MOD = 3;

    protected static $allCarts = [];
    protected static $id = 0;

    public function __construct($x, $y, $direction)
    {
        $this->name = chr(65 + self::$id);
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        $this->crossDirection = self::TURN_LEFT;
        self::$allCarts[$this->name] = $this;
        self::$id += 1;
    }

    public function getPosition()
    {
        return [$this->x, $this->y];
    }

    public function getNextPosition()
    {
        switch ($this->direction) {
            case self::DIR_UP:
                return [$this->x, $this->y - 1];
            case self::DIR_RIGHT:
                return [$this->x + 1, $this->y];
            case self::DIR_DOWN:
                return [$this->x, $this->y + 1];
            case self::DIR_LEFT:
                return [$this->x - 1, $this->y];
        }
    }

    public function move($newTile, $x, $y)
    {
        // for '-' and '|' nothing special must be done
        switch ($newTile) {
            case '/':
                if ($this->direction == self::DIR_UP) {
                    $this->direction = self::DIR_RIGHT;
                } elseif ($this->direction == self::DIR_LEFT) {
                    $this->direction = self::DIR_DOWN;
                } elseif ($this->direction == self::DIR_RIGHT) {
                    $this->direction = self::DIR_UP;
                } elseif ($this->direction == self::DIR_DOWN) {
                    $this->direction = self::DIR_LEFT;
                }

                break;
            case '\\':
                if ($this->direction == self::DIR_UP) {
                    $this->direction = self::DIR_LEFT;
                } elseif ($this->direction == self::DIR_RIGHT) {
                    $this->direction = self::DIR_DOWN;
                } elseif ($this->direction == self::DIR_LEFT) {
                    $this->direction = self::DIR_UP;
                } elseif ($this->direction == self::DIR_DOWN) {
                    $this->direction = self::DIR_RIGHT;
                }
                break;
            case '+':
                $this->direction = ($this->direction + $this->crossDirection + self::DIR_MOD) % self::DIR_MOD;
                $this->crossDirection = (($this->crossDirection + 2) % self::TURN_MOD) - 1;
                break;
        }

        $this->x = $x;
        $this->y = $y;
        $this->hasMoved = true;
    }

    public function hasMoved()
    {
        return $this->hasMoved;
    }

    public function draw()
    {
        $dirs = [
            self::DIR_UP => '^',
            self::DIR_RIGHT => '>',
            self::DIR_DOWN => 'v',
            self::DIR_LEFT => '<',
        ];
        return $dirs[$this->direction];
    }

    public static function resetMoves()
    {
        /** @var Cart $cart */
        foreach (self::$allCarts as $cart) {
            $cart->hasMoved = false;
        }
    }

    public static function count()
    {
        return count(self::$allCarts);
    }

    public static function removeCart($cart)
    {
        unset(self::$allCarts[$cart->name]);
    }

    public static function getCarts()
    {
        return self::$allCarts;
    }
}
