<?php
define('APP_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APP_LANG', 'zh-CN');

require SYSTEM_PATH .'bootstrap.php';

Ocase\App::run();