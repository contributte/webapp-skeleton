<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;
use stdClass;

/**
 * @mixin BasePresenter
 */
trait TFlashMessage
{

	/**
	 * @internal
	 * @param string $message
	 * @param string $type
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function flashMessage($message, $type = 'info'): stdClass
	{
		if ($this->isAjax()) {
			$this->redrawControl('flashes');
		}

		return parent::flashMessage($message, $type);
	}

	public function flashInfo(string $message): stdClass
	{
		return $this->flashMessage($message, 'info');
	}

	public function flashSuccess(string $message): stdClass
	{
		return $this->flashMessage($message, 'success');
	}

	public function flashWarning(string $message): stdClass
	{
		return $this->flashMessage($message, 'warning');
	}

	public function flashError(string $message): stdClass
	{
		return $this->flashMessage($message, 'danger');
	}

}
