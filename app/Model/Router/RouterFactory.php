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
		$this->router[] = $list = new RouteList('Admin');
		$list->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildFront(): void
	{
		$this->router[] = $list = new RouteList('Front');
		$list->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildMailing(): void
	{
		$this->router[] = $list = new RouteList('Mailing');
		$list->addRoute('mailing/<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildPdf(): void
	{
		$this->router[] = $list = new RouteList('Pdf');
		$list->addRoute('pdf/<presenter>/<action>[/<id>]', 'Home:default');
	}

}
