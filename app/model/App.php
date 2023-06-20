<?php declare(strict_types = 1);

namespace App\Model;

final class App
{

	public const DESTINATION_FRONT_HOMEPAGE = ':Front:Home:';
	public const DESTINATION_ADMIN_HOMEPAGE = ':Admin:Home:';
	public const DESTINATION_SIGN_IN = ':Admin:Sign:in';
	public const DESTINATION_AFTER_SIGN_IN = self::DESTINATION_ADMIN_HOMEPAGE;
	public const DESTINATION_AFTER_SIGN_OUT = self::DESTINATION_FRONT_HOMEPAGE;

}
