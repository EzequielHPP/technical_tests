<?php
include "../boot.php";

function generateHashtag($str)
{
    $str = trim($str);
    $str = preg_replace('/\s+/', ' ', $str);

    if (empty($str) || strlen($str) >= 140) {
        return false;
    }
    $words = explode(' ', ucwords($str));
    return '#' . implode('', $words);
}


$test = (new \App\Test());
$test->assertEquals('#HelloThereThanksForTryingMyKata', generateHashtag(' Hello there thanks for trying my Kata'));
$test->assertEquals('#HelloWorld', generateHashtag('    Hello     World   '));

$test->assertEquals(false, generateHashtag(''), 'Expected an empty string to return false');
$test->assertEquals(false, generateHashtag(str_repeat(' ', 200)), "Still an empty string");
$test->assertEquals('#Codewars', generateHashtag('Codewars'),
    'Should handle a single word and add a hashtag at the beginning.');
$test->assertEquals('#Codewars', generateHashtag('Codewars      '), 'Should handle trailing whitespace.');
$test->assertEquals('#CodewarsIsNice', generateHashtag('Codewars Is Nice'), 'Should remove spaces.');
$test->assertEquals('#CodewarsIsNice', generateHashtag('codewars is nice'),
    'Should capitalize first letters of words.');
$test->assertEquals('#CodeWars', generateHashtag('Code' . str_repeat(' ', 140) . 'wars'));
$test->assertEquals(false,
    generateHashtag('Looooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooong Cat'),
    'Should return false if the final word is longer than 140 chars.');
$test->assertEquals("#A" . str_repeat("a", 138), generateHashtag(str_repeat("a", 139)), "Should work");
$test->assertEquals(false, generateHashtag(str_repeat("a", 140)), "Too long");
