<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

interface IUserGridFactory extends IWidgetFactory
{

	public function create(): UserGrid;

}
