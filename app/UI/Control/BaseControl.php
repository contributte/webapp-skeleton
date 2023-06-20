<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\Model\Latte\TemplateProperty;
use App\UI\Modules\Base\BasePresenter;
use Nette\Application\UI\Control;

/**
 * @property-read TemplateProperty $template
 * @property-read BasePresenter $presenter
 */
abstract class BaseControl extends Control
{

}
