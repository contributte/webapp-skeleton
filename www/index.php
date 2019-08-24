<?php declare(strict_types = 1);

// Uncomment this line if you must temporarily take down your site for maintenance.
//require '.maintenance.php';

// Let bootstrap create Dependency Injection container.
$container = require_once __DIR__ . '/../app/bootstrap.php';

// Run application.
$container->getByType(Nette\Application\Application::class)->run();
