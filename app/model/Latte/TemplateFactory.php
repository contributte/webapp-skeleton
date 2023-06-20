<?php declare(strict_types = 1);

namespace App\Model\Latte;

use App\Model\Security\SecurityUser;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NetteTemplateFactory;
use Nette\Caching\Storage;
use Nette\Http\IRequest;

final class TemplateFactory extends NetteTemplateFactory
{

	/** @var LatteFactory */
	private $latteFactory;

	/** @var SecurityUser */
	private $user;

	public function __construct(
		LatteFactory $latteFactory,
		IRequest $httpRequest,
		SecurityUser $user,
		Storage $cacheStorage,
		string $templateClass = null
	)
	{
		parent::__construct($latteFactory, $httpRequest, $user, $cacheStorage, $templateClass);
		$this->latteFactory = $latteFactory;
		$this->user = $user;
	}

	public function createTemplate(Control $control = null, string $class = null): Template
	{
		/** @var Template $template */
		$template = parent::createTemplate($control);

		// Remove default $template->user for prevent misused
		unset($template->user);

		// Assign new variables
		$template->_user = $this->user;
		$template->_template = $template;
		$template->_filters = new FilterExecutor($this->latteFactory->create());

		return $template;
	}

}
