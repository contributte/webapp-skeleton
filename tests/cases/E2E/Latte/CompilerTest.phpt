<?php declare(strict_types = 1);

namespace Tests\Cases\E2E\Latte;

use App\Bootstrap;
use App\Model\Latte\TemplateFactory;
use Contributte\Tester\Toolkit;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Finder;
use SplFileInfo;
use Tester\Assert;
use Throwable;

require_once __DIR__ . '/../../../bootstrap.php';

Toolkit::test(function (): void {
	$container = Bootstrap::boot()->createContainer();

	/** @var ITemplateFactory $templateFactory */
	$templateFactory = $container->getByType(ITemplateFactory::class);
	Assert::type(TemplateFactory::class, $templateFactory);

	/** @var Template $template */
	$template = $templateFactory->createTemplate();
	$finder = Finder::findFiles('*.latte')->from(APP_DIR);

	try {
		/** @var SplFileInfo $file */
		foreach ($finder as $file) {
			$template->getLatte()->warmupCache($file->getRealPath());
		}
	} catch (Throwable $e) {
		Assert::fail(sprintf('Template compilation failed ([%s] %s)', $e::class, $e->getMessage()));
	}
});
