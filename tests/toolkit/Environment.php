<?php declare(strict_types = 1);

namespace Tests\Toolkit;

use RuntimeException;
use Tester\Environment as TEnvironment;
use Tester\Helpers as THelpers;

class Environment
{

	public const TEMP_DIR = 'TEMP_DIR';
	public const CACHE_DIR = 'CACHE_DIR';

	/**
	 * Magic setup method
	 */
	public static function setup(string $dir): void
	{
		self::setupTester();
		self::setupTimezone();
		self::setupVariables($dir);
		self::setupGlobalVariables();
	}

	/**
	 * Configure environment
	 */
	public static function setupTester(): void
	{
		TEnvironment::setup();
	}

	/**
	 * Configure timezone
	 */
	public static function setupTimezone(string $timezone = 'Europe/Prague'): void
	{
		date_default_timezone_set($timezone);
	}

	/**
	 * Configure variables
	 */
	public static function setupVariables(string $rootDir): void
	{
		if (!is_dir($rootDir)) {
			die(sprintf('Provide existing folder, "%s" does not exist.', $rootDir));
		}

		$tmpDir = realpath($rootDir) . '/tmp';

		// Temp, cache directories
		define('TEMP_DIR', $tmpDir . '/tests/' . getmypid() . '/' . md5(uniqid((string) microtime(true), true) . lcg_value() . mt_rand(0, 20) . microtime()));
		define('CACHE_DIR', $tmpDir . '/cache');
		ini_set('session.save_path', $tmpDir . '/sessions');

		// Create folders
		self::purge($tmpDir);
	}

	/**
	 * @param mixed $value
	 */
	public static function setupVariable(string $variable, $value): void
	{
		define($variable, $value);
	}

	/**
	 * Configure global variables
	 */
	public static function setupGlobalVariables(): void
	{
		$_SERVER = array_intersect_key($_SERVER, array_flip([
			'PHP_SELF',
			'SCRIPT_NAME',
			'SERVER_ADDR',
			'SERVER_SOFTWARE',
			'HTTP_HOST',
			'DOCUMENT_ROOT',
			'OS',
			'argc',
			'argv',
		]));
		$_SERVER['REQUEST_TIME'] = 1234567890;
		$_ENV = $_GET = $_POST = [];
	}

	public static function mkdir(string $dir, int $mode = 0777, bool $recursive = true): void
	{
		if (is_dir($dir) === false && @mkdir($dir, $mode, $recursive) === false) {
			clearstatcache(true, $dir);
			$error = error_get_last();
			if (is_dir($dir) === false && !file_exists($dir) === false) {
				throw new RuntimeException(sprintf("Unable to create directory '%s'. " . ($error['message'] ?? null), $dir));
			}
		}
	}

	public static function rmdir(string $dir): void
	{
		if (!is_dir($dir)) {
			return;
		}

		self::purge($dir);
		@rmdir($dir);
	}

	private static function purge(string $dir): void
	{
		if (!is_dir($dir)) {
			self::mkdir($dir);
		}

		THelpers::purge($dir);
	}

}
