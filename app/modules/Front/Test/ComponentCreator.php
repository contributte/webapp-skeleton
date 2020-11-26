<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

final class ComponentCreator
{

	/** @var IWidgetFactory[] */
	private $factories = [];

	/** @var array */
	private $listeners = [
		'onCreate' => [],
		'onAnchor' => [],
	];

	public function add(string $name, IWidgetFactory $factory): void
	{
		$this->factories[$name] = $factory;
	}

	public function onCreate(string $widget, callable $cb): void
	{
		$this->listeners['onCreate'][$widget][] = $cb;
	}
	public function onAnchor(string $widget, callable $cb): void
	{
		$this->listeners['onAnchor'][$widget][] = $cb;
	}

	public function create(string $name): ?Widget
	{
		if (!isset($this->factories[$name])) return null;

		$listeners = $this->listeners['onCreate'][$name] ?? [];
		foreach ($listeners as $listener) {
			$args = $listener();
		}

		/** @var Widget $widget */
		$widget = $this->factories[$name]->create();

		if (count($listeners) > 0) {
			call_user_func_array([$widget, 'setProps'], (array) $args);
		}

		$widget->onAnchor[] = function (Widget $widget) use ($name): void {
			$listeners = $this->listeners['onAnchor'][$name] ?? [];
			foreach ($listeners as $listener) {
				$listener($widget);
			}
		};

		return $widget;
	}

}
