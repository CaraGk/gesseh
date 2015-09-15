<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var $loader ClassLoader
 */
/* Dirty fix for open_basedir issue on ISPconfig server */
ini_set('open_basedir', $_SERVER["DOCUMENT_ROOT"]);

$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
