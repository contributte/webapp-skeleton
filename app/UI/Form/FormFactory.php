<?php declare(strict_types = 1);

namespace App\UI\Form;

final class FormFactory
{

	public function forFrontend(): BaseForm
	{
		return $this->create();
	}

	public function forBackend(): BaseForm
	{
		return $this->create();
	}

	private function create(): BaseForm
	{
		return new BaseForm();
	}

}
