<?php declare(strict_types = 1);

namespace Tests\Integration\Database\Entity;

use App\Model\Database\EntityManager;
use Doctrine\ORM\Tools\SchemaValidator;
use Nette\DI\Container;
use Tester\Assert;

/** @var Container $container */
$container = require_once __DIR__ . '/../../../../bootstrap.container.php';

test(function () use ($container): void {
	/** @var EntityManager $em */
	$em = $container->getByType(EntityManager::class);

	// Validation
	$validator = new SchemaValidator($em);
	$validations = $validator->validateMapping();
	foreach ($validations as $fails) {
		foreach ((array) $fails as $fail) {
			Assert::fail($fail);
		}
	}

	Assert::count(0, $validations);
});
