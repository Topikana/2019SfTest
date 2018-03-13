<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 08/03/18
 * Time: 16:42
 */
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
return $loader;
