<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

interface IUserDetailFactory extends IWidgetFactory
{

	public function create(): UserDetail;

}
