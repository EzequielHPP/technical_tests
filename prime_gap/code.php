<?php
include "../boot.php";
function gap($gap, $offset, $end)
{
    $lastPrime = null;
    for ($number = $offset; $number <= $end; $number++) {
        if (isNumberPrime($number)) {
            if (!empty($lastPrime) && $number - $lastPrime === $gap) {
                return [$lastPrime, $number];
            }
            $lastPrime = $number;
        }
    }
    return null;
}

function isNumberPrime($number): bool
{
    $isPrime = true;
    $highestIntegralSquareRoot = floor(sqrt($number));
    for ($x = 2; $x <= $highestIntegralSquareRoot; $x++) {
        if ($number % $x === 0) {
            $isPrime = false;
            break;
        }
    }
    return $isPrime;
}

$test = (new \App\Test());
$test->assertEquals([101, 103], gap(2, 100, 110));
$test->assertEquals([103, 107], gap(4, 100, 110));
$test->assertEquals(null, gap(6, 100, 110));
$test->assertEquals([359, 367], gap(8, 300, 400));
$test->assertEquals([337, 347], gap(10, 300, 400));
