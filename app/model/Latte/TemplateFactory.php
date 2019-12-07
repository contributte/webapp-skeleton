<?php declare(strict_types = 1);

namespace App\Model\Latte;

use App\Model\Security\SecurityUser;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NetteTemplateFactory;
use Nette\Caching\IStorage;
use Nette\Http\IRequest;

final class TemplateFactory extends NetteTemplateFactory
{

	/** @var SecurityUser */
	private $user;

	public function __construct(
		ILatteFactory $latteFactory,
		IRequest $httpRequest,
		SecurityUser $user,
		IStorage $cacheStorage,
		string $templateClass = null
	)
	{
		parent::__construct($latteFactory, $httpRequest, $user, $cacheStorage, $templateClass);
		$this->user = $user;
	}

	public function createTemplate(Control $control = null): ITemplate
	{
		/** @var Template $template */
		$template = parent::createTemplate($control);

		// Remove default $template->user for prevent misused
		unset($template->user);

		// Assign new variables
		$template->_user = $this->user;
		$template->_template = $template;
		$template->_filters = new FilterExecutor($template->getLatte());

		return $template;
	}

}
