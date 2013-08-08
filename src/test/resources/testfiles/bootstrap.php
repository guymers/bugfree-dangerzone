<?php

if (!class_exists("Composer\Autoload\ClassLoader")) {
	require BASEDIR . "/vendor/composer/ClassLoader.php";
}

use Composer\Autoload\ClassLoader;

$classLoader = new ClassLoader();
$classLoader->set("test", __DIR__);
$classLoader->register();
