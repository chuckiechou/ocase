<?php

namespace Ocase;

class BaseServices {

    /**
     * Cache for instance of any services that
     * have been requested as a "shared" instance.
     * Keys should be lowercase service names.
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * The Autoloader class is the central class that handles our
     * spl_autoload_register method, and helper methods.
     *
     * @return Autoloader
     */
    public static function autoloader(bool $getShared = true) {
        if ($getShared) {
            if (empty(static::$instances['autoloader'])) {
                static::$instances['autoloader'] = new Autoloader();
            }

            return static::$instances['autoloader'];
        }

        return new Autoloader();
    }
}