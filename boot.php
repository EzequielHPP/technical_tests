<?php

define('__ROOT__',__DIR__);

$folders = [
    'App'
];

foreach ($folders as $folder) {
    foreach (glob(__ROOT__.'/'.$folder . '/*.php') as $filename) {
        spl_autoload_register(function ($filename) {
            include __ROOT__ . '/' . $filename . '.php';
        });
    }
}
