<?php
include "../boot.php";

function perimeter($n)
{
    $total = 0;
    $cache = [0 => 0, 1 => 1];
    for ($x = 0; $x <= $n+1; $x++) {
        $value = calculate($x, $cache);
        $total += $value;
    }
    return 4 * $total;
}

function calculate($n, &$cache){

    if (!isset($cache[$n])) {
        $cache[$n] = calculate($n - 1, $cache) + calculate($n - 2, $cache);
    }
    return $cache[$n];
}

$test = (new \App\Test());

$test->assertEquals(80, perimeter(5), 'Failed: ');
$test->assertEquals(216, perimeter(7), 'Failed: ');
$test->assertEquals(3226062132197568, perimeter(70), 'Failed: ');
