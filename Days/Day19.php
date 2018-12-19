<?php


namespace AoC2018\Days;

// Reuse definitions of day16!
class Day19 extends Day16
{
    protected $title = 'Go With The Flow';

    protected function part1()
    {
        list($ip, $instructions) = $this->getInstructions();

        $pointer = 0;
        $registers = array_fill(0, 6, 0);
        while (true) {
            if (!isset($instructions[$pointer])) {
                break;
            }
            $instruction = $instructions[$pointer];
            $method = 'opCode' . ucfirst($instruction[0]);
            $registers[$ip] = $pointer;
            $this->$method($registers, $instruction);
            $pointer = $registers[$ip] + 1;
        }

        return $registers[0];
    }

    // just setting the first register to 1 will make
    // the program run _very_ long.
    // the actual solution seems to be, to understand
    // what the program is trying to do and optimizing that.
    protected function part2()
    {
        list($ip, $instructions) = $this->getInstructions();

        $pointer = 0;
        $registers = array_fill(0, 6, 0);
        $registers[0] = 1;
        while (true) {
            if (!isset($instructions[$pointer])) {
                break;
            }
            $instruction = $instructions[$pointer];
            $method = 'opCode' . ucfirst($instruction[0]);
            $registers[$ip] = $pointer;
            $this->$method($registers, $instruction);
            $pointer = $registers[$ip] + 1;
        }

        return $registers[0];
    }

    protected function readinput($name = '')
    {
        $file = $this->getInputFile($name);
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function getInstructions()
    {
        $lines = $this->readinput();
        preg_match('~\d~', array_shift($lines), $ip);

        $instructions = [];
        foreach ($lines as $line) {
            $instructions[] = explode(' ', $line);
        }

        return [$ip[0], $instructions];
    }
}
