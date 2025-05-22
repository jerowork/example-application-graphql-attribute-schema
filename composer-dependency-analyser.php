<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

$config->addPathToScan(__DIR__ . '/bin', isDev: false);
$config->addPathToScan(__DIR__ . '/config', isDev: true);
$config->addPathToScan(__DIR__ . '/public', isDev: false);

$config->ignoreErrorsOnPackage('symfony/console', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnPackage('symfony/dotenv', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnPackage('symfony/flex', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnPackage('symfony/runtime', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnPackage('symfony/yaml', [ErrorType::UNUSED_DEPENDENCY]);

$config->ignoreErrorsOnExtension('ext-ctype', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnExtension('ext-iconv', [ErrorType::UNUSED_DEPENDENCY]);

$config->ignoreErrorsOnPackage('guzzlehttp/psr7', [ErrorType::UNUSED_DEPENDENCY]);
$config->ignoreErrorsOnPackage('php-http/discovery', [ErrorType::UNUSED_DEPENDENCY]);

return $config;
