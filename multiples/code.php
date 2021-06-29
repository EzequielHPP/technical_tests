<?php
include "../boot.php";

function solution($number)
{
    $total = 0;
    if ($number > 0) {
        for ($x = 1; $x < $number; $x++) {
            if ($x % 3 === 0 || $x % 5 === 0) {
                $total += $x;
            }
        }
    }
    return $total;
}

$test = (new \App\Test());
$test->assertEquals(23, solution(10));
