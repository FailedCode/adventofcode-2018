<?php


namespace AoC2018\Days;


class Day4 extends AbstractDay
{
    protected $title = 'Repose Record';

    protected function part1()
    {
        $guards = $this->calculateGuardSleep();

        usort($guards, function ($a, $b) {
            if ($a['sleep'] == $b['sleep']) {
                return 0;
            }
            // notice the >
            // sort the highest to position 0
            return ($a['sleep'] > $b['sleep']) ? -1 : 1;
        });

        $guard = $guards[0];
        $minute = array_keys($guard['minutes'], max($guard['minutes']))[0];
        return $guard['id'] * $minute;
    }

    protected function calculateGuardSleep()
    {
        $table = $this->getOrderedTimeTable();
        $guards = [];
        $currentGuard = '';
        $sleepStart = 0;
        foreach ($table as $record) {
            if (isset($record['id'])) {
                $currentGuard = $record['id'];
                if (!isset($guards[$currentGuard])) {
                    $guards[$currentGuard] = [
                        'id' => $currentGuard,
                        'sleep' => 0,
                        'minutes' => array_fill(0, 60, 0),
                    ];
                }
            }

            if (isset($record['state'])) {
                if ($record['state'] == 0) {
                    $sleepStart = $record['i'];
                } elseif ($record['state'] == 1) {
                    for ($i = $sleepStart; $i < $record['i']; $i++) {
                        $guards[$currentGuard]['sleep'] += 1;
                        $guards[$currentGuard]['minutes'][$i] += 1;
                    }
                }
            }
        }

        return $guards;
    }

    protected function part2()
    {
        $minutesMax = array_fill(0, 60, 0);
        $minutesGuard = array_fill(0, 60, 0);

        $guards = $this->calculateGuardSleep();
        foreach ($guards as $guard) {
            foreach ($guard['minutes'] as $minute => $sleep) {
                if ($sleep > $minutesMax[$minute]) {
                    $minutesMax[$minute] = $sleep;
                    $minutesGuard[$minute] = $guard['id'];
                }
            }
        }

        $minuteMax = array_keys($minutesMax, max($minutesMax))[0];
        $guard = $minutesGuard[$minuteMax];

        return $minuteMax * $guard;
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');

    }

    protected function getOrderedTimeTable()
    {
        $lines = $this->readinput();
        $records = [];
        foreach ($lines as $line) {
            $record = [];
            if (preg_match('~\[(\d+)-(\d+)-(\d+)\s(\d+):(\d+)\]~', $line, $match)) {
                $record['y'] = (int)$match[1];
                $record['m'] = (int)$match[2];
                $record['d'] = (int)$match[3];
                $record['h'] = (int)$match[4];
                $record['i'] = (int)$match[5];
                $record['date'] = "{$match[1]}{$match[2]}{$match[3]}{$match[4]}{$match[5]}";
            }
            if (preg_match('~#(\d+)~', $line, $match)) {
                $record['id'] = $match[1];
            }
            if (preg_match('~falls asleep~', $line, $match)) {
                $record['state'] = 0;
            }
            if (preg_match('~wakes up~', $line, $match)) {
                $record['state'] = 1;
            }
            $records[] = $record;
        }

        // for the sorting to work, we need to compare the whole date string
        // 03 !== 3
        usort($records, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });
        return $records;

    }
}
