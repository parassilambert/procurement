<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

AnnotationRegistry::registerFile( dirname( __DIR__ ).'/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php' );
AnnotationRegistry::registerAutoloadNamespace("Symfony\Component\Validator\Constraints", __DIR__ . '/../vendor/symfony/symfony/src');
AnnotationRegistry::registerAutoloadNamespace("Symfony\Bridge\Doctrine\Validator\Constraints", __DIR__ . '/../vendor/symfony/symfony/src');
AnnotationRegistry::registerAutoloadNamespace("sensio/framework-extra-bundle/Configuration", __DIR__ . '/../vendor');
AnnotationRegistry::registerFile( dirname( __DIR__ ).'/vendor/sensio/framework-extra-bundle/Configuration/Route.php' );
AnnotationRegistry::registerFile( dirname( __DIR__ ).'/vendor/sensio/framework-extra-bundle/Configuration/Template.php' );
AnnotationRegistry::registerFile( dirname( __DIR__ ).'/vendor/sensio/framework-extra-bundle/Configuration/Security.php' );

return $loader;
