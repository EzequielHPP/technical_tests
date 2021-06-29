<?php

namespace App;

class Test
{
    private $callback;


    public function assertEquals($match, $output, $failMessage = 'Failed ')
    {
        echo "Testing: " . json_encode($match) . " -> ";
        if (is_array($match) && is_array($output)) {
            $result = array_intersect($match, $output);
            if (count($result) === count($match)) {
                echo "Passed\n";
            } else {
                echo $failMessage . ' - (' . json_encode($output) . ")\n";
            }
        } else {
            if ($match === $output) {
                echo "Passed\n";
            } else {
                echo $failMessage . ' - (' . json_encode($output) . ")\n";
            }
        }
    }
}
