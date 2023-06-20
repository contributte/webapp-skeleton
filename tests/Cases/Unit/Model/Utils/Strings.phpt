<?php declare(strict_types = 1);

use App\Model\Utils\Strings;
use Tester\Assert;

require_once __DIR__ . '/../../../../bootstrap.php';

// Strings::slashless
test(function (): void {
	$input = 'foo//bar/////test/asd';
	$expected = 'foo/bar/test/asd';

	$result = Strings::slashless($input);

	Assert::same($expected, $result);
});
