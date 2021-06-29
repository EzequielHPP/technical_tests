<?php
include "../boot.php";

function divisors($integer)
{
    $output = [];
    if (check_prime($integer) === 1) {
        return $integer . ' is prime';
    }
    foreach (range(2, $integer - 1) as $number) {
        if (is_int($integer / $number)) {
            $output[] = $number;
        }
    }

    return $output;
}

function check_prime($num)
{
    if ($num == 1) {
        return 0;
    }
    for ($i = 2; $i <= $num / 2; $i++) {
        if ($num % $i == 0) {
            return 0;
        }
    }
    return 1;
}

$test = (new \App\Test());
$test->assertEquals([3, 5], divisors(15));
$test->assertEquals([2, 3, 4, 6], divisors(12));
$test->assertEquals('13 is prime', divisors(13));
