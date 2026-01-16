<?php declare(strict_types = 1);

namespace App\UI\Form;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;

class BaseForm extends Form
{

	public function addNumeric(string $name, ?string $label = null): TextInput
	{
		$input = self::addText($name, $label);
		$input->addCondition(self::Filled)
			->addRule(self::MaxLength, null, 255)
			->addRule(self::Numeric);

		return $input;
	}

}
