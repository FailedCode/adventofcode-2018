<?php


namespace AoC2018\Days;

class Day16 extends AbstractDay
{
    protected $title = 'Chronal Classification';

    protected function part1()
    {
        list($before, $instructions, $after) = $this->getInput(1);
        $samplesLikeThreeOpcodes = 0;

        $opcodes = [
            'Addr', 'Addi',
            'Mulr', 'Muli',
            'Banr', 'Bani',
            'Borr', 'Bori',
            'Setr', 'Seti',
            'Gtir', 'Gtri', 'Gtrr',
            'Eqir', 'Eqri', 'Eqrr'
        ];
        $length = count($before);
        for ($i = 0; $i < $length; $i++) {
            $opCodeCount = 0;
            foreach ($opcodes as $opcode) {
                $method = "opCode$opcode";
                $registers = $before[$i];
                $this->$method($registers, $instructions[$i]);
                if ($registers == $after[$i]) {
                    $opCodeCount += 1;
                    if ($opCodeCount > 2) {
                        $samplesLikeThreeOpcodes += 1;
                        break;
                    }
                }
            }
        }
        return $samplesLikeThreeOpcodes;
    }

    protected function opCodeAddr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] + $registers[$instructions[2]];
    }

    protected function opCodeAddi(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] + $instructions[2];
    }

    protected function opCodeMulr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] * $registers[$instructions[2]];
    }

    protected function opCodeMuli(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] * $instructions[2];
    }

    protected function opCodeBanr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] & $registers[$instructions[2]];
    }

    protected function opCodeBani(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] & $instructions[2];
    }

    protected function opCodeBorr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] | $registers[$instructions[2]];
    }

    protected function opCodeBori(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]] | $instructions[2];
    }

    protected function opCodeSetr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $registers[$instructions[1]];
    }

    protected function opCodeSeti(&$registers, $instructions)
    {
        $registers[$instructions[3]] = $instructions[1];
    }

    protected function opCodeGtir(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($instructions[1] > $registers[$instructions[2]]) ? 1 : 0;
    }

    protected function opCodeGtri(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($registers[$instructions[1]] > $instructions[2]) ? 1 : 0;
    }

    protected function opCodeGtrr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($registers[$instructions[1]] > $registers[$instructions[2]]) ? 1 : 0;
    }

    protected function opCodeEqir(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($instructions[1] == $registers[$instructions[2]]) ? 1 : 0;
    }

    protected function opCodeEqri(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($registers[$instructions[1]] == $instructions[2]) ? 1 : 0;
    }

    protected function opCodeEqrr(&$registers, $instructions)
    {
        $registers[$instructions[3]] = ($registers[$instructions[1]] == $registers[$instructions[2]]) ? 1 : 0;
    }

    protected function part2()
    {
    }

    protected function readinput()
    {
        $file = $this->getInputFile();
        return array_filter(explode("\n", file_get_contents($file)), 'strlen');
    }

    protected function getInput($part)
    {
        $lines = $this->readinput();

        $before = [];
        $instructions = [];
        $after = [];
        $i = 0;
        foreach ($lines as $line) {
            preg_match_all('~\d+~', $line, $match);

            if ($i % 3 == 0) {
                if (strpos($line, 'Before') === false) {
                    break;
                }
                $before[] = $match[0];
            } elseif ($i % 3 == 1) {
                $instructions[] = $match[0];
            } elseif ($i % 3 == 2) {
                $after[] = $match[0];
            }
            $i += 1;
        }

        if ($part == 1) {
            return [$before, $instructions, $after];
        }

        return false;
    }
}
