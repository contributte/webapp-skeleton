<?php declare(strict_types = 1);

namespace App\Modules\Base;

use App\Model\App;
use Nette\Application\UI\ComponentReflection;
use Nette\Security\IUserStorage;

abstract class SecuredPresenter extends BasePresenter
{

	/**
	 * @param ComponentReflection|mixed $element
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function checkRequirements($element): void
	{
		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === IUserStorage::INACTIVITY) {
				$this->flashInfo('You have been logged out for inactivity');
			}

			$this->redirect(
				App::DESTINATION_SIGN_IN,
				['backlink' => $this->storeRequest()]
			);
		}
	}

}
