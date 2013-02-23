<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add(array(__DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}


AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
