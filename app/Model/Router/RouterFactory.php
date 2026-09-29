<?php declare(strict_types = 1);

namespace App\Model\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	private RouteList $router;

	public function __construct()
	{
		$this->router = new RouteList();
	}

	public function create(): RouteList
	{
		$this->buildMailing();
		$this->buildPdf();
		$this->buildAdmin();
		$this->buildFront();

		return $this->router;
	}

	protected function buildAdmin(): void
	{
		$list = new RouteList('Admin');
		$this->router->add($list);
		$list->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildFront(): void
	{
		$list = new RouteList('Front');
		$this->router->add($list);
		$list->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildMailing(): void
	{
		$list = new RouteList('Mailing');
		$this->router->add($list);
		$list->addRoute('mailing/<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildPdf(): void
	{
		$list = new RouteList('Pdf');
		$this->router->add($list);
		$list->addRoute('pdf/<presenter>/<action>[/<id>]', 'Home:default');
	}

}
