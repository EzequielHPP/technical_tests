<?php
include "../boot.php";

function createPhoneNumber($numbersArray)
{
    $chunks = array_chunk($numbersArray, 3);
    $last = array_slice(array_merge($chunks[2], $chunks[3]), 0, 4);

    return '(' . implode($chunks[0]) . ') ' . implode($chunks[1]) . '-' . implode($last);
}

$test = (new \App\Test());
$test->assertEquals('(123) 456-7890', createPhoneNumber([1, 2, 3, 4, 5, 6, 7, 8, 9, 0]));
$test->assertEquals('(111) 111-1111', createPhoneNumber([1, 1, 1, 1, 1, 1, 1, 1, 1, 1]));

