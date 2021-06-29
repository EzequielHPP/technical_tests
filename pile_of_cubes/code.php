<?php
include "../boot.php";

function findNb($m)
{
    $total = 1;
    $check = 0;
    while ($check <= $m) {
        $check += $total * $total * $total;
        if ($check === $m) {
            return $total;
        }
        ++$total;
    }
    return -1;
}


$test = (new \App\Test());
$test->assertEquals(45, findNb(1071225));
$test->assertEquals(2022, findNb(4183059834009));
$test->assertEquals(-1, findNb(24723578342962));
$test->assertEquals(4824, findNb(135440716410000));
