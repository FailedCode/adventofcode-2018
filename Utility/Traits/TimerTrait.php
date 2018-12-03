<?php

namespace AoC2018\Utility\Traits;

trait TimerTrait
{
    /**
     * Measure time used
     * @var float
     */
    protected $time = 0.0;

    /**
     * Save the current time
     */
    public function resetTimer()
    {
        $this->time = microtime(true);
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return microtime(true) - $this->time;
    }

    /**
     * @param null|float $duration
     * @return string
     */
    public function getDurationFormated($duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->getDuration();
        } else {
            $duration = (float)$duration;
        }
        if ($duration < 0.01) {
            return (int)($duration * 1000000) . ' µs';
        }
        if ($duration < 1) {
            return (int)($duration * 1000) . ' ms';
        }
        if ($duration < 3) {
            return number_format($duration, 2) . ' s';
        }
        return (int)$duration . ' s';
    }
}
