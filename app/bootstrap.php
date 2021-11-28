<?php declare(strict_types = 1);

use Contributte\Bootstrap\ExtraConfigurator;
use Nette\DI\Compiler;
use Tracy\Debugger;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new ExtraConfigurator();
$configurator->setTempDirectory(__DIR__ . '/../var/tmp');

$configurator->onCompile[] = function (ExtraConfigurator $configurator, Compiler $compiler): void {
	// Add env variables to config structure
	$compiler->addConfig(['parameters' => $configurator->getEnvironmentParameters()]);
};

// According to NETTE_DEBUG env
$configurator->setEnvDebugMode();

// Enable tracy and configure it
$configurator->enableTracy(__DIR__ . '/../var/log');
Debugger::$errorTemplate = __DIR__ . '/resources/tracy/500.phtml';

// Provide some parameters
$configurator->addParameters([
	'rootDir' => realpath(__DIR__ . '/..'),
	'appDir' => __DIR__,
	'wwwDir' => realpath(__DIR__ . '/../www'),
]);

// Load development or production config
if (getenv('NETTE_ENV', true) === 'dev') {
	$configurator->addConfig(__DIR__ . '/../config/env/dev.neon');
} else {
	$configurator->addConfig(__DIR__ . '/../config/env/prod.neon');
}

$configurator->addConfig(__DIR__ . '/../config/local.neon');

return $configurator->createContainer();
