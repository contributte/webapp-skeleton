<?php declare(strict_types = 1);

namespace Tests\Integration\Latte;

use App\Model\Latte\TemplateFactory;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Container;
use Nette\Utils\Finder;
use SplFileInfo;
use Tester\Assert;
use Throwable;

/** @var Container $container */
$container = require_once __DIR__ . '/../../../bootstrap.container.php';

test(function () use ($container): void {
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
		Assert::fail(sprintf('Template compilation failed ([%s] %s)', get_class($e), $e->getMessage()));
	}
});
