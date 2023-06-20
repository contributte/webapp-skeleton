<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\Model\Exception\Runtime\InvalidStateException;
use App\UI\Modules\Base\BasePresenter;

/**
 * @mixin BasePresenter
 */
trait TModuleUtils
{

	/**
	 * Gets module name
	 */
	public function getModuleName(): string
	{
		$name = $this->getName();

		// Validate presenter has a proper name
		if ($name === null) {
			throw new InvalidStateException('Presenter doesn\'t have a name');
		}

		$parts = explode(':', $name);

		return current($parts);
	}

	/**
	 * Is current module active?
	 *
	 * @param string $module Module name
	 */
	public function isModuleCurrent(string $module): bool
	{
		return strpos($this->getAction(true), $module) !== false;
	}

	/**
	 * Gets template dir
	 */
	public function getTemplateDir(): string
	{
		$fileName = self::getReflection()->getFileName();

		// Validate if class is not in PHP core
		if ($fileName === false) {
			throw new InvalidStateException('Class is defined in the PHP core or in a PHP extension');
		}

		$realpath = realpath(dirname($fileName) . '/../templates');

		// Validate if file exists
		if ($realpath === false) {
			throw new InvalidStateException('File does not exist');
		}

		return $realpath;
	}

}
