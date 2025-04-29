<?php
include '../boot.php';

class RangeExtraction
{
    private array $list = [];
    private array $ranges = [];

    public function __construct(array $list)
    {
        if(empty($list)){
            throw new \InvalidArgumentException('List cannot be empty');
        }

        usort($list, function($a, $b) {
            return $a <=> $b;
        });
        $this->list = $list;

    }

    public function solution(): string
    {
        $currentIndex = 0;
        foreach ($this->list as $integer) {
            $this->addIntegerToRanges($integer, $currentIndex);
        }
        $this->ranges = array_map(function($range) {
            if (count($range) > 2) {
                return $range[0] . '-' . end($range);
            }
            return implode(',', $range);
        }, $this->ranges);
        return implode(',', $this->ranges);
    }

    private function addIntegerToRanges(int $integer, int &$currentIndex): void
    {
        // get current range
        $currentRange = $this->ranges[$currentIndex] ?? null;
        if ($currentRange === null) {
            $this->createRange($integer);
            $currentIndex = count($this->ranges) - 1;
            return;
        }

        // check if the integer is the next in the range
        if ($integer === end($currentRange) + 1) {
            $this->addToRange($currentIndex, $integer);
            return;
        }

        $this->splitSmallerRange($currentIndex);

        $this->createRange($integer);
        $currentIndex = count($this->ranges) - 1;
    }

    private function createRange(int $integer): void
    {
        $this->ranges[] = [$integer];
    }

    private function addToRange(int $rangeIndex, int $integer): void
    {
        $this->ranges[$rangeIndex][] = $integer;
    }

    private function splitSmallerRange(int &$rangeIndex): void
    {
        // check if the range has less than 3 elements
        if (count($this->ranges[$rangeIndex]) < 3) {
            // then we need to split the range into different ranges
            $range = $this->ranges[$rangeIndex];
            $this->ranges[$rangeIndex] = [$range[0]];
            array_shift($range);
            foreach ($range as $integer) {
                $this->createRange($integer);
            }
            $rangeIndex = count($this->ranges) - 1;
        }
    }

}

function solution(array $list): string
{
    return (new RangeExtraction($list))->solution();
}



$test = (new \App\Test());

$test->assertEquals('-6,-3-1,3-5,7-11,14,15,17-20', solution([-6, -3, -2, -1, 0, 1, 3, 4, 5, 7, 8, 9, 10, 11, 14, 15, 17, 18, 19, 20]), 'FAILED ');
$test->assertEquals('-3--1,2,10,15,16,18-20', solution([-3, -2, -1, 2, 10, 15, 16, 18, 19, 20]), 'FAILED ');

