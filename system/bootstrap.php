<?php

use Ocase\Services;
use Ocase\Config\AutoloadConfig;

require SYSTEM_PATH . 'Config/AutoloadConfig.php';
require SYSTEM_PATH . 'Autoloader.php';
require SYSTEM_PATH . 'BaseServices.php';
require SYSTEM_PATH . 'Services.php';

Services::autoloader()->initialize(new AutoloadConfig())->register();