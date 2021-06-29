<?php
include "../boot.php";

function orderWeight($str): string
{
    $newNumbers = splitAndSum($str);

    foreach ($newNumbers as $total => $numbers) {
        usort($numbers, 'customValueSort');
        $newNumbers[$total] = implode(' ', $numbers);
    }
    return implode(' ', $newNumbers);
}

function customValueSort($a, $b)
{
    if ($a === $b) {
        return 0;
    }
    list($newA, $newB) = matchLength($a, $b, 1);
    return $newA <=> $newB;

}

function matchLength($a, $b, $length = 1)
{
    $lengths = array_map('strlen', [(string)$a, (string)$b]);
    $newA = substr($a, 0, $length);
    $newB = substr($b, 0, $length);
    if ($newA === $newB && $length < max($lengths)) {
        list($newA, $newB) = matchLength($a, $b, ++$length);
    }
    return [$newA, $newB];
}

function splitAndSum(string $str): array
{
    $numbers = explode(" ", $str);
    $newNumbers = [];
    foreach ($numbers as $num) {
        $values = str_split($num);
        $total = array_sum($values);
        if (!array_key_exists($total, $newNumbers)) {
            $newNumbers[$total] = [];
        }
        $newNumbers[$total][] = (string)$num;
    }
    ksort($newNumbers);
    return $newNumbers;
}

$test = (new \App\Test());

$test->assertEquals("2000 103 123 4444 99", orderWeight("103 123 4444 99 2000"), 'FAILED //result//');
$test->assertEquals("11 11 2000 10003 22 123 1234000 44444444 9999",
    orderWeight("2000 10003 1234000 44444444 9999 11 11 22 123"), 'FAILED //result//');
$test->assertEquals("1 2 200 4 4 6 6 7 7 18 27 72 81 9 91 425 31064 7920 67407 96488 34608557 71899703",
    orderWeight("1 200 2 4 4 6 6 7 7 18 27 72 81 9 91 425 31064 7920 67407 96488 34608557 71899703"),
    'FAILED //result//');
