<?php
include "../boot.php";

function decode_morse(string $code): string
{
    $output = [];
    $words = explode('   ', strtoupper(trim($code)));
    foreach ($words as $word) {
        $output[] = decodeLetters(explode(' ', $word));
    }

    return strtoupper(implode($output, ' '));
}

function decodeLetters(array $letters): string
{
    $output = '';
    $morse = [
        'A' => '.-',
        'B' => '-...',
        'C' => '-.-.',
        'D' => '-..',
        'E' => '.',
        'F' => '..-.',
        'G' => '--.',
        'H' => '....',
        'I' => '..',
        'J' => '.---',
        'K' => '-.-',
        'L' => '.-..',
        'M' => '--',
        'N' => '-.',
        'O' => '---',
        'P' => '.--.',
        'Q' => '--.-',
        'R' => '.-.',
        'S' => '...',
        'T' => '-',
        'U' => '..-',
        'V' => '...-',
        'W' => '.--',
        'X' => '-..-',
        'Y' => '-.--',
        'Z' => '--..',

        '0' => '-----',
        '1' => '.----',
        '2' => '..---',
        '3' => '...--',
        '4' => '....-',
        '5' => '.....',
        '6' => '-....',
        '7' => '--...',
        '8' => '---..',
        '9' => '----.',

        '.' => '.-.-.-',
        ',' => '--..--',
        '?' => '..--..',
        "'" => '.----.',
        '!' => '-.-.--',
        '/' => '-..-.',
        '(' => '-.--.',
        ')' => '-.--.-',
        '&' => '.-...',
        ':' => '---...',
        ';' => '-.-.-.',
        '=' => '-...-',
        '+' => '.-.-.',
        '-' => '-....-',
        '_' => '..--.-',
        '"' => '.-..-.',
        '$' => '...-..-',
        '@' => '.--.-.',
        'SOS' => '...---...'
    ];

    foreach ($letters as $letter) {
        $output .= array_search(strtoupper($letter), $morse);
    }

    return strtoupper($output);
}

$test = (new \App\Test());
$test->assertEquals('HEY JUDE', decode_morse('.... . -.--   .--- ..- -.. .'));
$test->assertEquals('SOS', decode_morse('... --- ...'));
