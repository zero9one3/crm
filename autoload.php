<?php

spl_autoload_register(function ($class) {

    $paths = [
        __DIR__ . '/core/',
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/services/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
