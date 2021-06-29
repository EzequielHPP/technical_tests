<?php

include "../boot.php";

function parse_molecule(string $formula): array
{
    $formula = trim(str_replace(['{', '[', '}', ']'], ['(', '(', ')', ')'], $formula));
    $output = countMolecules($formula);
    ksort($output);
    return $output;
}

function applyMultiplier(&$array, $multiplier)
{
    foreach ($array as $key => $value) {
        $array[$key] = $value * $multiplier;
    }
}

function mergeArrays()
{
    $arrays = func_get_args();
    $keys = array_keys(array_reduce($arrays, function ($keys, $arr) {
        return $keys + $arr;
    }, []));
    $sums = [];

    foreach ($keys as $key) {
        $sums[$key] = array_reduce($arrays, function ($sum, $arr) use ($key) {
            return $sum + @$arr[$key];
        });
    }
    return $sums;
}

function countMolecules(string $formula): array
{
    $output = [];
    // Get me all the (xx)x? values
    preg_match_all('/(\()(.*)(\))([0-9]){0,2}/', $formula, $matches);

    // If we match any brackets, then recursively call itself to process that block
    if (array_key_exists(1, $matches)) {
        foreach ($matches[0] as $index => $match) {
            $newMultiplier = array_key_exists(4, $matches) ? $matches[4][$index] : 1;
            $subQueryOutput = countMolecules($matches[2][$index]);
            applyMultiplier($subQueryOutput, $newMultiplier);
            $output = mergeArrays($output, $subQueryOutput);
            $formula = str_replace($match, '', $formula);
        }
    }
    // Match the normal connotation for the Molecules Xx(x)
    preg_match_all('/([A-Z]{1}[a-z]{0,1})([0-9]{0,2})/', $formula, $realElementMatches);
    foreach ($realElementMatches[0] as $index => $entries) {
        $letter = $realElementMatches[1][$index];
        $quantity = array_key_exists(2,
            $realElementMatches) && !empty($realElementMatches[2][$index]) ? $realElementMatches[2][$index] : 1;
        if (!array_key_exists($letter, $output)) {
            $output[$letter] = 0;
        }

        $output[$letter] += $quantity;
    }
    return $output;
}

$test = (new \App\Test());

$test->assertEquals(['H' => 2, 'O' => 1], parse_molecule('H2O'),
    'Your function should correctly parse a molecule of water');
$test->assertEquals(['Mg' => 1, 'O' => 2, 'H' => 2], parse_molecule('Mg(OH)2'),
    'Your function should correctly parse magnesium hydroxide');
$test->assertEquals(['K' => 4, 'O' => 14, 'N' => 2, 'S' => 4], parse_molecule('K4[ON(SO3)2]2'),
    'Your function should work for Fremy\'s salt');
$test->assertEquals(['C' => 6, 'H' => 12, 'O' => 6], parse_molecule('C6H12O6'),
    'Your function should correctly parse D-Glucose');
$test->assertEquals(['O'=> 48, 'Co'=> 24, 'Be'=> 16, 'Cu'=> 5, 'C'=> 44, 'B'=> 8, 'As'=> 2], parse_molecule('As2(Be4C5[BCo3(CO2)3]2)4Cu5'),
    'Your function should correctly parse cyclopentadienyliron dicarbonyl dimer');
$test->assertEquals(['C' => 14, 'H' => 10, 'Fe' => 2, 'O' => 4], parse_molecule('C14H10Fe2O4'),
    'Your function should correctly parse cyclopentadienyliron dicarbonyl dimer');
