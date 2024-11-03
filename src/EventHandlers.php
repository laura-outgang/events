<?php declare(strict_types=1);

namespace Outgang\Events;

use Outgang\Events\Contracts\EventHandlerInterface;
use Outgang\Events\Contracts\EventHandlersInterface;
use Outgang\Iterables\IterableList;


class EventHandlers implements EventHandlersInterface
{
    public readonly \SplObjectStorage $list;

	
	public function __construct(iterable $handlers=[])
	{
		$this->list = new \SplObjectStorage();

		foreach ($handlers as $handler) {
			$this->attach($handler);
		}
	}


	public function attach(EventHandlerInterface $handler): void
	{
		$this->list->attach($handler);
	}


	public function detach(EventHandlerInterface $handler): void
	{
		$this->list->detach($handler);
	}


	public function merge(iterable $handlers): void
	{
		foreach ($handlers as $handler) {
			$this->attach($handler);
		}
	}

	
	public function count(): int
	{
		return $this->list->count();
	}


	public function getIterator(): \Traversable
	{
		yield from $this->list;
	}


	public function get(string $name, ?State $state=null): IterableList
	{
		$list = new IterableList($this->list);
		$condition = fn($i) => $i->name === $name && (null === $state || $i->state === $state);
		return $list->if($condition)->sort(fn($i) => $i->priority());
	}


	public function remove(string $name='', ?callable $callback=null)
	{
		foreach ($this($name, $callback) as $handler) {
			$this->detach($handler);
		}
	}


	public function has(string $name, ?Callable $callback=null): bool
	{
		return $this($name, $callback)->first() !== null;
	}


	public function clear(): void
	{
		foreach ($this->list as $handler) {
			$this->detach($handler);
		}
	}


	public function __invoke(string $name, State $state): IterableList
	{
		return $this->get($name, $state);
	}


	public function __clone()
	{
		$this->list = clone $this->list;
	}
}
