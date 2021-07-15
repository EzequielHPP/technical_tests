<?php

define('__ROOT__', __DIR__);

$folders = [
    'App'
];

function dd($message, $die = true)
{
    if (!is_string($message)) {
        $message = json_encode($message);
    }
    var_dump($message);
    if ($die) {
        die();
    }
}


function dump($message)
{
    dd($message, false);
}

foreach ($folders as $folder) {
    foreach (glob(__ROOT__ . '/' . $folder . '/*.php') as $filename) {
        spl_autoload_register(function ($filename) {
            include __ROOT__ . '/' . $filename . '.php';
        });
    }
}
