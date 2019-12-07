<?php declare(strict_types = 1);

namespace App\Modules\Admin;

use App\Model\App;
use App\Modules\Base\SecuredPresenter;
use Nette\Application\UI\ComponentReflection;

abstract class BaseAdminPresenter extends SecuredPresenter
{

	/**
	 * @param ComponentReflection|mixed $element
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function checkRequirements($element): void
	{
		parent::checkRequirements($element);

		if (!$this->user->isAllowed('Admin:Home')) {
			$this->flashError('You cannot access this with user role');
			$this->redirect(App::DESTINATION_FRONT_HOMEPAGE);
		}
	}

}
