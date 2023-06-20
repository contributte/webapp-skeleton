<?php

namespace PHPSTORM_META {
	override(\App\Model\Database\EntityManagerDecorator::getRepository(0), map([
		'\App\Domain\User\User' => \App\Domain\User\UserRepository::class,
	]));
}
