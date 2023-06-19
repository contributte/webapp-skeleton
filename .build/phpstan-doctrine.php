<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

return App\Bootstrap::boot()
	->createContainer()
	->getByType(Doctrine\ORM\EntityManagerInterface::class);
