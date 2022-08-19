<?php

namespace casezor;

use InvalidArgumentException;

class Autoload {
    protected $prefixes = [];
    protected $classmap = [];
    protected $files = [];

    public function initialize(array $config) {
        $this->prefixes = [];
        $this->classmap = [];
        $this->files = [];

        // We have to have one or the other, though we don't enforce the need
        // to have both present in order to work.
        if (empty($config['psr4']) && empty($confi['classmap'])) {
            throw new InvalidArgumentException('Config array must contain either the \'psr4\' key or the \'classmap\' key.');
        }

        if (isset($config['psr4'])) {
            $this->addNamespace($config['psr4']);
        }

        if (isset($config['classmap'])) {
            $this->classmap = $config['classmap'];
        }

        if (isset($config['files'])) {
            $this->files = $config['files'];
        }

        return $this;
    }

    public function addNamespace($namespace, ?string $path = null) {
        if (is_array($namespace)) {
            foreach ($namespace as $prefix => $namespacedPath) {
                $prefix = trim($prefix, '\\');

                if (is_array($namespacedPath)) {
                    foreach ($namespacedPath as $dir) {
                        $this->prefixes[$prefix][] = rtrim($dir, '\\/') . DIRECTORY_SEPARATOR;
                    }

                    continue;
                }

                $this->prefixes[$prefix][] = rtrim($namespacedPath, '\\/') . DIRECTORY_SEPARATOR;
            }
        } else {
            $this->prefixes[trim($namespace, '\\')][] = rtrim($path, '\\/') . DIRECTORY_SEPARATOR;
        }

        return $this;
    }

    public function register() {
        spl_autoload_register([$this, 'loadClass'], true, true);
        spl_autoload_register([$this, 'loadClassmap'], true, true);

        // Load our non-class files
        foreach ($this->files as $file) {
            if (is_string($file)) {
                $this->includeFile($file);
            }
        }
    }

    protected function loadClass(string $class) {
        $class = trim($class, '\\');
        $class = str_ireplace('.php', '', $class);

        return $this->loadInNamespace($class);
    }

    protected function loadInNamespace(string $class) {
        if (strpos($class, '\\') === false) {
            return false;
        }

        foreach ($this->prefixes as $namespace => $directories) {
            foreach ($directories as $directory) {
                $directory = rtrim($directory, '\\/');

                if (strpos($class, $namespace) === 0) {
                    $filePath = $directory . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($namespace))) . '.php';
                    $filename = $this->includeFile($filePath);

                    if ($filename) {
                        return $filename;
                    }
                }
            }
        }

        // never found a mapped file
        return false;
    }

    public function loadClassmap(string $class) {
        $file = $this->classmap[$class] ?? '';

        if (is_string($file) && $file !== '') {
            return $this->includeFile($file);
        }

        return false;
    }

    protected function includeFile(string $file) {
        $file = $this->sanitizeFilename($file);

        if (is_file($file)) {
            include_once $file;

            return $file;
        }

        return false;
    }

    public function sanitizeFilename(string $filename): string {
        // Only allow characters deemed safe for POSIX portable filenames.
        // Plus the forward slash for directory separators since this might be a path.
        // http://pubs.opengroup.org/onlinepubs/9699919799/basedefs/V1_chap03.html#tag_03_278
        // Modified to allow backslash and colons for on Windows machines.
        $filename = preg_replace('/[^0-9\p{L}\s\/\-\_\.\:\\\\]/u', '', $filename);

        // Clean up our filename edges.
        return trim($filename, '.-_');
    }
}