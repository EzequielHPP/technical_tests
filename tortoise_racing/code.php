<?php
include '../boot.php';

function race(int $v1, int $v2, int $g)
{
    if ($v1 > $v2) {
        return null;
    }
    $speedDifference = $v2 - $v1;
    $timeToCatchUp = secondsToTime($g * 3600 / $speedDifference);
    return [(int)$timeToCatchUp[0], (int)$timeToCatchUp[1], (int)$timeToCatchUp[2]];
}

function secondsToTime(int $seconds): array
{
    $beginningOftime = new \DateTime('@0');
    $currentSeconds = new \DateTime("@$seconds");
    return explode(':', $beginningOftime->diff($currentSeconds)->format('%h:%i:%s'));
}

$test = (new \App\Test());

$test->assertEquals([0, 32, 18], race(720, 850, 70), 'FAILED ');
$test->assertEquals([3, 21, 49], race(80, 91, 37), 'FAILED ');
$test->assertEquals([2, 0, 0], race(80, 100, 40), 'FAILED ');
