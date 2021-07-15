<?php
include "../boot.php";

function isMerge($s, $part1, $part2): bool
{
    $s = trim($s);
    return checkPart($s, $part1) && checkPart($s, $part2) && containsAllCharacters($s, $part1, $part2) && containsSameCharacters($s, $part1, $part2);
}

function checkPart(string $string, string $part): bool
{
    $check = str_split($part);
    $lastIndex = 0;
    foreach ($check as $character) {
        $newIndex = strpos($string, $character, $lastIndex);
        if ($newIndex === false || $newIndex < $lastIndex) {
            return false;
        }
        $lastIndex = $newIndex;
    }
    return true;
}

function containsAllCharacters(string $string, string ...$parts): bool
{
    $parts = implode($parts);
    $originalString = str_split($string);
    $submittedCharacters = str_split($parts);
    return empty(array_diff($originalString, $submittedCharacters));
}

function containsSameCharacters(string $fullString, string ...$parts)
{
    $letters = str_split($fullString);
    $counter = [];
    foreach ($letters as $letter) {
        if (!array_key_exists($letter, $counter)) {
            $counter[(string)$letter] = 0;
        }
        $counter[(string)$letter]++;
    }

    foreach ($parts as $part) {
        $split = str_split($part);
        foreach ($split as $letter) {
            if (!array_key_exists((string)$letter, $counter)) {
                return false;
            }
            $counter[(string)$letter]--;
        }
    }
    foreach ($counter as $letter => $count) {
        if ($count < 0){
            return false;
        }
    }
    return true;
}


$test = (new \App\Test());
$test->assertEquals(true, isMerge('codewars', 'code', 'wars'));
$test->assertEquals(true, isMerge('codewars', 'cdw', 'oears'));
$test->assertEquals(false, isMerge('codewars', 'cod', 'wars'));
$test->assertEquals(false, isMerge('codewars', 'code', 'warss'));
$test->assertEquals(false, isMerge('codewars', 'code', 'wasr'));

