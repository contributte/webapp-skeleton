<?php declare(strict_types = 1);

use Ninjify\Nunjuck\Environment;

// Check composer && tester
if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

// Configure test environment
Environment::setup(__DIR__);
