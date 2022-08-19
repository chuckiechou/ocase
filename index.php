<?php
use casezor\App;
use casezor\Autoload;

define('ROOTPATH', __DIR__);
define('APP_NAMESPACE', 'app');

require_once ROOTPATH . '/system/autoload.php';

$config = [
    'psr4'  => [
        APP_NAMESPACE => ROOTPATH . 'app',
        'casezor'     => ROOTPATH . '/system',
    ],
    'files' => [
        ROOTPATH . '/app/functions.php',
    ],
];
$autoload = (new Autoload())->initialize($config);
$autoload->register();

App::run();

