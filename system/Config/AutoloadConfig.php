<?php

namespace Ocase\Config;

class AutoloadConfig {
    public $psr4 = [
        'Ocase' => SYSTEM_PATH,
        'App'   => APP_PATH, // To ensure filters, etc still found,
    ];

    public $classmap = [

    ];

    public $files = [

    ];
}