<?php declare(strict_types = 1);

use Nette\Configurator;

$rootDir = __DIR__ . '/..';

// Require base bootstrap
require_once __DIR__ . '/bootstrap.php';

// Create container
$configurator = new Configurator();
$configurator->setTempDirectory(TEMP_DIR);

$configurator->addConfig($rootDir . '/app/config/env/test.neon');
$configurator->addConfig($rootDir . '/app/config/config.local.neon');

// Setup debugMode of course!
$configurator->setDebugMode(true);

// Override to original wwwDir
$configurator->addParameters([
	'rootDir' => $rootDir,
	'appDir' => $rootDir . '/app',
	'wwwDir' => $rootDir . '/www',
]);

// Create test container
return $configurator->createContainer();
