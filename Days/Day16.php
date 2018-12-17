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
        list($before, $instructions, $after, $testprogramm) = $this->getInput(2);
        $opCodes = $this->findOpcodes($before, $instructions, $after);

        $registers = array_fill(0, 4, 0);
        foreach ($testprogramm as $instructions) {
            $method = "opCode" . $opCodes[$instructions[0]];
            $this->$method($registers, $instructions);
        }

        return $registers[0];
    }

    protected function findOpcodes($before, $instructions, $after)
    {
        $opCodeIDSuccess = [];
        $opcodes = [
            'Addr' => 0, 'Addi' => 0,
            'Mulr' => 0, 'Muli' => 0,
            'Banr' => 0, 'Bani' => 0,
            'Borr' => 0, 'Bori' => 0,
            'Setr' => 0, 'Seti' => 0,
            'Gtir' => 0, 'Gtri' => 0, 'Gtrr' => 0,
            'Eqir' => 0, 'Eqri' => 0, 'Eqrr' => 0,
        ];

        $length = count($before);
        for ($i = 0; $i < $length; $i++) {
            foreach ($opcodes as $opcode => &$count) {
                $method = "opCode$opcode";
                $registers = $before[$i];
                $this->$method($registers, $instructions[$i]);
                if ($registers == $after[$i]) {
                    $count += 1;
                    $codeNo = $instructions[$i][0];
                    if (!isset($opCodeIDSuccess[$codeNo])) {
                        $opCodeIDSuccess[$codeNo] = [];
                    }
                    if (!isset($opCodeIDSuccess[$codeNo][$opcode])) {
                        $opCodeIDSuccess[$codeNo][$opcode] = 0;
                    }
                    $opCodeIDSuccess[$codeNo][$opcode] += 1;
                }
            }
        }

        // use the opcode where the least (one) possible
        // opcode is recorded and remove it from the rest
        $opCodeIDtoName = [];
        while (!empty($opCodeIDSuccess)) {
            $min = min($opCodeIDSuccess);
            $opNo = array_keys($opCodeIDSuccess,$min)[0];
            $opcode = array_keys($min)[0];
            $opCodeIDtoName[$opNo] = $opcode;
            foreach ($opCodeIDSuccess as $key => &$item) {
                unset($item[$opcode]);
                if (count($item) == 0) {
                    unset($opCodeIDSuccess[$key]);
                }
            }
        }

        return $opCodeIDtoName;
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

        $testprogram = [];
        foreach ($lines as $line) {
            if ($i > 0) {
                $i -= 1;
                continue;
            }
            $testprogram[] = explode(' ', $line);
        }

        return [$before, $instructions, $after, $testprogram];
    }
}
