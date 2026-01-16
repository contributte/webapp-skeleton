<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\UI\Modules\Base\BasePresenter;
use stdClass;
use Stringable;

/**
 * @mixin BasePresenter
 */
trait TFlashMessage
{

	/**
	 * @param string|stdClass|Stringable $message
	 * @internal
	 */
	public function flashMessage(mixed $message, string $type = 'info'): stdClass
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
