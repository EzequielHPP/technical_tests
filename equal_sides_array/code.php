<?php

include "../boot.php";

function find_even_index(array $arr, $position = 0)
{
    $totalEntries = count($arr);
    if ($position >= $totalEntries) {
        return -1;
    }
    $left = array_sum(array_slice($arr, 0, $position));
    $right = array_sum(array_slice($arr, $position + 1, $totalEntries - $position));
    if ($left === $right) {
        return $position;
    }
    return find_even_index($arr, $position+1);
}

$test = (new \App\Test());

$test->assertEquals(3, find_even_index([1, 2, 3, 4, 3, 2, 1]));
$test->assertEquals(1, find_even_index([1, 100, 50, -51, 1, 1]));
$test->assertEquals(-1, find_even_index([1, 2, 3, 4, 5, 6]));
$test->assertEquals(3, find_even_index([20, 10, 30, 10, 10, 15, 35]));
$test->assertEquals(0, find_even_index([20, 10, -80, 10, 10, 15, 35]));
$test->assertEquals(6, find_even_index([10, -80, 10, 10, 15, 35, 20]));
$test->assertEquals(0, find_even_index([0, 0, 0, 0, 0]), "Should pick the first index if more cases are valid");
$test->assertEquals(3, find_even_index([-1, -2, -3, -4, -3, -2, -1]));
$test->assertEquals(-1, find_even_index(range(1, 100)));
$test->assertEquals(-1, find_even_index(range(-100, -1)));
