<?php

namespace App;

class Test
{
    private $callback;


    public function assertEquals($match, $output, $failMessage = 'Failed')
    {
        echo "Testing: " . json_encode($match) . " -> ";
        if ($match === $output) {
            echo "Passed\n";
        } else {
            echo str_replace('//result//', $output, $failMessage) . "\n";
        }
    }
}
